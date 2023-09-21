import './bootstrap';

// import Alpine from 'alpinejs';
//
// window.Alpine = Alpine;
//
// Alpine.start();

if(typeof classroomId !== 'undefined'){
    Echo.private('classroom.' + classroomId)
        .listen('.classwork-created', function(event){
            alert(event.title);
        });
}


//notification method make listen for not event without need to route long name
Echo.private('App.Models.User.' + userId)
    .notification(function($event){
         alert($event.title)
    });
