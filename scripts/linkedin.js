

$('#linkedin-button').on('click', function() {
    // Initialize with your OAuth.io app public key
    OAuth.initialize('86af6ozdsw3g0m');
    // Use popup for oauth
    OAuth.popup('linkedin2').then(linkedin => {
        console.log('linkedin:',linkedin);
        // Prompts 'welcome' message with User's email on successful login
        // #me() is a convenient method to retrieve user data without requiring you
        // to know which OAuth provider url to call
        linkedin.me().then(data => {
            console.log('me data:', data);
            alert('Linkedin says your email is:' + data.email + ".\nView browser 'Console Log' for more details");
        })
        // Retrieves user data from OAuth provider by using #get() and
        // OAuth provider url
        linkedin.get('/v1/people/~:(id,first-name,last-name,headline,picture-url,email-address,public-profile-url)?format=json').then(data => {
            console.log('self data:', data);
        })
    });
})


