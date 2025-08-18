pipeline {
    agent any

    parameters {
        string(name: 'PROJECT_DIR', defaultValue: 'UBMagerAPI' )
        string(name: 'SERVICE_NAME', defaultValue: 'app-ubmager')
    }

    stages {
        stage('Checkout Code') {
            steps {
                echo "Mengambil kode dari GitHub..."
                cleanWs()
                checkout scm
            }
        }

        stage('Sync, Build, and Deploy') {
            steps {
                echo "Mempersiapkan untuk deploy ke VPS..."
                sshagent(['jenkins-ssh-key']) {
                    sh """
                        echo "--- Sinkronisasi kode dari Jenkins ke /var/www/${params.PROJECT_DIR} ---"
                        rsync -avz --delete --exclude='.git/' --exclude='.env' ./ jenkins@localhost:/var/www/${params.PROJECT_DIR}/

                        # Sekarang, login ke VPS untuk menjalankan perintah Docker
                        ssh -o StrictHostKeyChecking=no jenkins@localhost '
                            
                            echo "--- Berhasil login ke VPS ---"
                        
                            cd /var/www/

                            echo "--- Membangun image baru dengan kode yang sudah disinkronkan ---"
                            docker-compose build --pull --no-cache ${params.SERVICE_NAME}

                            echo "--- Men-deploy semua layanan ---"
                            docker-compose up -d ${params.SERVICE_NAME}

                            echo "--- Membersihkan image Docker lama ---"
                            docker image prune -f
                        '
                    """
                }
            }
        }
    }

    post {
        success {
            echo 'Pipeline berhasil!'
        }
        failure {
            echo 'Pipeline GAGAL!'
        }
    }
}