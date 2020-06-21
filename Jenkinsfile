#!/usr/bin/env groovy

pipeline {
    environment {
        registry = "mkoziol/purpleclouds"
        registryCredential = 'dockerhub'
        dockerPhpImage = ''
        dockerTestImage = ''
        containerName = 'task-manager-php'
    }

    agent any

    stages {
        stage('Get code from SCM') {
            steps{
                checkout(
                    [$class: 'GitSCM', branches: [[name: '*/master']],
                     doGenerateSubmoduleConfigurations: false,
                     extensions: [],
                     submoduleCfg: [],
                     userRemoteConfigs: [[credentialsId: 'task_manager_repository', url: "git@github.com:Skiru/task-manager.git"]]]
                )
            }
        }

        stage('Building test image') {
          steps{
            script {
              dockerTestImage = docker.build("task-manager-test-container", "-f ./docker/php/Dockerfile-dev . --no-cache=true")
              dockerTestImage.withRun() {
                    container -> sh "ls -al"
              }
            }
          }
        }

        stage('Run unit tests') {
            steps{
                script{
                    dockerTestImage.withRun("--env-file=./.env.dist") { container ->
                        sh "docker exec ${container.id} php ./bin/phpunit -c ./phpunit.xml.dist"
                    }
                }
            }
        }

        stage('Building php image') {
          steps{
            script {
              dockerPhpImage = docker.build(registry + ":" + containerName + "-$BUILD_NUMBER", "-f ./docker/php/Dockerfile . --no-cache=true")
            }
          }
        }

        stage('Deploy php image to dockerhub') {
            steps{
                script {
                  docker.withRegistry( '', registryCredential ) {
                    dockerPhpImage.push()
                  }
                }
           }
        }

        stage('Remove Unused docker image') {
          steps{
            sh "docker rmi $registry:$containerName-$BUILD_NUMBER"
            sh "docker image prune -f"
          }
        }

        stage('Build task-manager application') {
            steps{
                sshagent (credentials: ['purple-clouds-server']) {
                    sh 'echo "docker login --username mkoziol --password pamietamhaslo;IMAGE_BUILD_TAG=$containerName-$BUILD_NUMBER; export IMAGE_BUILD_TAG; docker-compose -f /var/www/PurpleClouds/task-manager/docker-compose.yml up -d;" | ssh -o StrictHostKeyChecking=no -l root 77.55.222.35'
                }
            }
        }

    }
}
