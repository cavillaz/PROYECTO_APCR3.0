pipeline {
    agent any

    stages {
        stage('Clonar repositorio') {
            steps {
                git branch: 'main', url: 'https://github.com/cavillaz/PROYECTO_APCR3.0.git'
            }
        }

        stage('Construir contenedor') {
            steps {
                script {
                    sh 'docker build .'
                }
            }
        }

        stage('Levantar con Docker Compose') {
            steps {
                script {
                    sh 'docker-compose up -d'
                }
            }
        }
    }
}
