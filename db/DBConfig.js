import { initializeApp } from "firebase/app";
import { getDatabase } from "firebase/database";

// GasByGas's Firebase configuration
const firebaseConfig = {
    apiKey: "AIzaSyBytcUUPrDVNen5yzKp9_52sSrD3VGNkr8",
    authDomain: "gasbygas-97e19.firebaseapp.com",
    databaseURL: "https://gasbygas-97e19-default-rtdb.firebaseio.com",
    projectId: "gasbygas-97e19",
    storageBucket: "gasbygas-97e19.firebasestorage.app",
    messagingSenderId: "1030736631035",
    appId: "1:1030736631035:web:73607eb3747cd071ed8c5e",
    measurementId: "G-NVB7CMY0LD"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const database = getDatabase(app);

export { app, database };

