// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getMessaging, getToken } from "firebase/messaging";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
const firebaseConfig = {
    apiKey: "AIzaSyAOgFQEnzkgY5oMdbS35cwUfyvygWYay_I",
    authDomain: "gaza-skey-geeks-classroom.firebaseapp.com",
    projectId: "gaza-skey-geeks-classroom",
    storageBucket: "gaza-skey-geeks-classroom.appspot.com",
    messagingSenderId: "390366254407",
    appId: "1:390366254407:web:d174642d1ceb599a9da862"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);

const messaging = getMessaging(app);
getToken(messaging, { vapidKey: 'BHg92kEHIc0vg4WuH-UtfaA2ckBSUlDcYyegGnKnsxsUbJobMvw2DaVjBrFdSwvdInmrTIlQohcfu3R77PeGKvo' }).then((currentToken) => {
    if (currentToken) {

        $.post('/api/v1/devices', {
            token: currentToken,
            _token: _token,
        },() => {})
    } else {
        // Show permission request UI
        console.log('No registration token available. Request permission to generate one.');
        // ...
    }
}).catch((err) => {
    console.log('An error occurred while retrieving token. ', err);
    // ...
});


