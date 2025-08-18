pipeline {
    
    agent any

    environment {
        DOT_ENV = credentials('ubmager-env-prod')
    }
    
    stages {
        stage('Checkout Code from GitHub') {
            steps {
                echo 'Mengambil kode terbaru...'
                checkout scm
            }
        }

        stage('Create .env from Credentials') {
            steps {
                echo 'Writing .env file from Jenkins credentials...'
                
                sh 'echo "$DOT_ENV" > .env'

                echo '--- Menampilkan 5 baris pertama dari .env yang baru dibuat ---'
                sh 'head -n 5 .env' 
                
            }
        }

        stage('Build and Deploy Application') {
            steps {
                echo '--- MEMBANGUN IMAGE APLIKASI BARU ---'
                sh 'docker compose build'

                echo '--- MEN-DEPLOY SEMUA LAYANAN ---'
                sh 'docker compose up -d'

                echo '--- MEMBERSIHKAN IMAGE DOCKER LAMA ---'
                sh 'docker image prune -f'
            }
        }
    }

    post {
        always {
            cleanWs()
        }
        success {
            echo 'Pipeline berhasil!'
        }
        failure {
            echo 'Pipeline GAGAL!'
        }
    }
}
