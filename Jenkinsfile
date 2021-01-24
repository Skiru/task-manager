#!/usr/bin/env groovy

pipeline {
    environment {
        HOME = "${WORKSPACE}"
        REGISTRY = "mkoziol/purpleclouds"
        REGISTRY_CREDENTIALS = 'dockerhub'
        GITHUB_CREDENTIALS = 'github-credential'
        PHP_IMAGE = ""
        ASSETS_IMAGE = ""
        PHP_IMAGE_NAME = "task-manager-php"
        FULL_PHP_IMAGE_NAME = "${REGISTRY}:${PHP_IMAGE_NAME}-${BUILD_NUMBER}"
    }

    agent any

    stages {
        stage('Clean environment') {
            steps{
                sh '''
                git reset --hard HEAD
                git clean -fdx
                '''
            }
        }

        stage('Get code from SCM') {
            steps{
                checkout(
                    [$class: 'GitSCM', branches: [[name: '*/master']],
                     doGenerateSubmoduleConfigurations: false,
                     extensions: [],
                     submoduleCfg: [],
                     userRemoteConfigs: [[credentialsId: "${GITHUB_CREDENTIALS}", url: "git@github.com:Skiru/task-manager.git"]]]
                )
            }
        }

        stage('Building php image') {
          steps{
            script {
              PHP_IMAGE = docker.build(FULL_PHP_IMAGE_NAME, "-f ./docker/php/Dockerfile . --no-cache")
            }
          }
        }

        stage('Deploy php image to dockerhub') {
            steps{
                script {
                  docker.withRegistry( '', REGISTRY_CREDENTIALS ) {
                    PHP_IMAGE.push()
                  }
                }
           }
        }

        stage('Remove Unused docker image') {
          steps{
            sh "docker rmi ${env.FULL_PHP_IMAGE_NAME}"
            sh "docker image prune -f -a"
          }
        }

        stage('Build task-manager application') {
            steps{
                sshagent (credentials: ['purple-clouds-server']) {
                    sh 'echo \
                    "docker login --username mkoziol --password pamietamhaslo;\
                    export TASK_MANAGER_PHP_IMAGE_BUILD_TAG=${FULL_PHP_IMAGE_NAME};\
                    docker-compose -f /var/www/PurpleClouds/task-manager/docker-compose.yml up -d;\
                    docker image prune -a -f || true;"\
                    | ssh -o StrictHostKeyChecking=no -l root 77.55.194.92;'
                }
            }
        }

    }
}
