import { get, ref, child } from "firebase/database";
import { database } from "../db/DBConfig";
import AsyncStorage from "@react-native-async-storage/async-storage";
import CryptoJS from "crypto-js";
import { router } from "expo-router";
import { useState } from "react";
import React from "react";
import { ImageBackground, View, TextInput, TouchableOpacity, Text, StyleSheet } from "react-native";
import Icon from "react-native-vector-icons/Ionicons";

// Function to hash input password
const hashPassword = (password: string) => {
  return CryptoJS.SHA256(password).toString(CryptoJS.enc.Hex);
};

const loginCustomer = async (email: string, password: string) => {
  try {
    const dbRef = ref(database);
    const snapshot = await get(child(dbRef, "consumers/"));
    if (snapshot.exists()) {
      const consumers = snapshot.val();
      const consumerArray = Object.keys(consumers).map(key => ({
        consumerId: key,
        ...consumers[key]
      }));

      // Hash the input password
      const hashedInputPassword = hashPassword(password);

      // Find the user by email
      const _user = consumerArray.find(
        (consumer: any) => consumer.email === email && consumer.password === hashedInputPassword
      );

      if (_user) {
        console.log("User Found", _user);
        await AsyncStorage.setItem("email", _user.email);
        await AsyncStorage.setItem("name", _user.name);
        await AsyncStorage.setItem("contact", _user.contact);
        await AsyncStorage.setItem("address", _user.address);
        await AsyncStorage.setItem("district", _user.district);
        await AsyncStorage.setItem("nic", _user.nic);
        await AsyncStorage.setItem("outlet_id", _user.outlet_id);
        await AsyncStorage.setItem("consumer_id", _user.consumerId);
        await AsyncStorage.setItem("password", _user.password);

        alert("Customer Login Successfully");
        router.push("/homepage");
      } else {
        alert("Invalid email or password");
      }
    } else {
      alert("No consumers found");
    }
  } catch (error) {
    console.error("Error retrieving data:", error);
    alert("An error occurred. Please try again later.");
  }
};

    const CustomerLogin = () => {
        const [email, setEmailAddress] = useState('');
        const [password, setPassword] = useState('');
        const [isPasswordVisible, setIsPasswordVisible] = useState(false);
    
        const togglePasswordVisibility = () => {
            setIsPasswordVisible(!isPasswordVisible);
        };
    
        return (
            <ImageBackground source={require('../assets/images/sky.jpg')} style={styles.background}>
                <View style={styles.container}>
                    <View style={styles.loginBox}>
                        <Text style={styles.title}>Login to Your Account</Text>
                        <TextInput
                            style={styles.input}
                            placeholder="Email Address"
                            placeholderTextColor="#888"
                            onChangeText={setEmailAddress}
                            value={email}
                        />
                        <View style={styles.passwordContainer}>
                            <TextInput
                                style={styles.passwordInput}
                                value={password}
                                onChangeText={setPassword}
                                placeholder="Password"
                                secureTextEntry={!isPasswordVisible}
                            />
                            <TouchableOpacity onPress={togglePasswordVisibility}>
                                <Icon
                                    name={isPasswordVisible ? 'eye-off-outline' : 'eye-outline'}
                                    size={20}
                                    color="#888"
                                />
                            </TouchableOpacity>
                        </View>
                        <TouchableOpacity style={styles.button} onPress={() => loginCustomer(email, password)}>
                            <Text style={styles.buttonText}>Login</Text>
                        </TouchableOpacity>
                        <TouchableOpacity style={styles.signupLink} onPress={() => router.push("/customerregister") }>
                            <Text style={styles.signupText}>Don't have an account? Sign Up</Text>
                        </TouchableOpacity>
                    </View>
                </View>
            </ImageBackground>
        );
    };
    
    export default CustomerLogin;


const styles = StyleSheet.create({
    background: {
        flex: 1,
        resizeMode: 'cover',
        justifyContent: 'center',
    },
    container: {
        flex: 1, justifyContent: 'center', alignItems: 'center',
    },
    loginBox: {
        backgroundColor: 'white',
        padding: 20,
        borderRadius: 10,
        width: '85%',
        shadowColor: '#000',
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.2,
        shadowRadius: 5,
        elevation: 5,
        alignItems: 'center',
    },
    title: {
        fontSize: 24, fontWeight: 'bold',
        marginBottom: 20, color: '#000',
        textAlign: 'center',
    },
    input: {
        width: '100%',
        backgroundColor: '#f9f9f9',
        borderWidth: 1, borderColor: '#ccc', borderRadius: 5,
        padding: 10,
        marginBottom: 15,
        fontSize: 16,
    },
    passwordContainer: {
        flexDirection: 'row',
        alignItems: 'center',
        borderWidth: 1,
        borderColor: '#ccc',
        borderRadius: 5,
        backgroundColor: '#f9f9f9',
        paddingHorizontal: 10,
        width: '100%',
        marginBottom: 15,
    },
    passwordInput: {
        flex: 1, paddingVertical: 10,
    },
    button: {
        backgroundColor: "#2776D1",
        paddingVertical: 12, borderRadius: 5,
        width: '100%', alignItems: 'center',
    },
    buttonText: {
        color: "white", fontWeight: 'bold', textTransform: 'uppercase',
    },
    signupLink: {
        marginTop: 15,
    },
    signupText: {
        fontWeight: 'bold',
        textAlign: "center",
        fontSize: 16,
        color: '#2776D1',
    },
});