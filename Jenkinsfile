node {
    def app

    stage('Clone repository') {
        /* Let's make sure we have the repository cloned to our workspace */

        checkout scm
    }

    stage('Build image') {
        /* This builds the actual image; synonymous to
         * docker build on the command line */
        /*app = docker.build("my-image:${env.BUILD_ID}", "-f Reporter/Dockerfile")*/
		app = docker.build("chrisashtear/zomboid-reporter", "-f Reporter/Dockerfile")
		app2 = docker.build("chrisashtear/zomboid-reporter-frontend", "-f frontend/Dockerfile")
    }

    stage('Push image') {
        /* Finally, we'll push the image with two tags:
         * First, the incremental build number from Jenkins
         * Second, the 'latest' tag.
         * Pushing multiple tags is cheap, as all the layers are reused. */
        docker.withRegistry('https://registry.hub.docker.com', 'docker-hub-credentials') {
            app.push("${env.BUILD_NUMBER}")
            app.push("latest")
			app2.push("${env.BUILD_NUMBER}")
            app2.push("latest")
        }
    }
}