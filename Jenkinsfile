pipeline {
    agent any

    stages {
        stage('Test SSH Connection to VPS') {
            steps {
                echo 'Mempersiapkan untuk tes koneksi..'
                sshagent(['vps-ssh-key']) {
                    sh '''
                        # -v akan menampilkan log debug yang detail
                        ssh -v -o StrictHostKeyChecking=no jenkins@localhost '
                            echo "---"
                            echo "--- KONEKSI BERHASIL! INI ISI FOLDER /home/jenkins/: ---"
                            echo "---"
                            ls -l
                            echo "---"
                            echo "--- TES SELESAI ---"
                        '
                    '''
                }
            }
        }
    }
}