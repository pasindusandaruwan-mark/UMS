
     // single-active selection in left side bar
        document.querySelectorAll('.toggle').forEach(toggle => {
            toggle.addEventListener('click', function() {
               document.querySelectorAll('.toggle').forEach(t => t.classList.remove('on'));   // Find all toggles and turn them OFF     
                 this.classList.add('on'); // Turn the clicked toggle ON
    });
});
        // Menu item selection
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function() {
                document.querySelectorAll('.menu-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
            });
        });

      
        const form = document.getElementById('registration-form');
       const message = document.getElementById('success-message');

    if (form && message) {
    form.addEventListener('submit', function(event) {
        // Prevents the page from reloading
        event.preventDefault();
        
        // Show the success message
        message.style.display = 'block';

        // Clear the form fields
        form.reset(); 

        //  Hide the message again after 4 seconds
        setTimeout(() => {
            message.style.display = 'none';
        }, 4000); 
    });
}