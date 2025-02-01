import { StyleSheet, View, Text, TextInput, TouchableOpacity, Platform, Button, ScrollView } from 'react-native';
import React, { useState } from 'react';
import { router } from 'expo-router';
import { Ionicons as Icon } from "@expo/vector-icons";
import { ref, get, child } from 'firebase/database';
import { database } from "../db/DBConfig";
import AsyncStorage from '@react-native-async-storage/async-storage';

export default function LoginScreen() {
    const [email, setEmailAddress] = useState('');
    const [password, setPassword] = useState('');
    const [isPasswordVisible, setPasswordVisible] = useState(false);

    const togglePasswordVisibility = () => {
        setPasswordVisible(!isPasswordVisible);
    };

    const loginCustomer = async () => {
        try {
            const dbRef = ref(database);
            const snapshot = await get(child(dbRef, "consumers/"));

            if (snapshot.exists()) {
                const consumers = snapshot.val();
                const consumerArray: any[] = Object.values(consumers);

                const isValidUser = consumerArray.some(
                    (consumer) => consumer.email === email && consumer.password === password
                );

                const _user = consumerArray.find(
                    (consumer) => consumer.email === email && consumer.password === password
                );

                if (_user) {
                     const outletsRef = ref(database, "outlets/");
                    const outletsSnapshot = await get(outletsRef);

                    let outletName = '';

                    if(outletsSnapshot.exists()){
                        const outletsData = outletsSnapshot.val();
                        for (const outletId in outletsData) {
                            if (outletId === _user.outlet_id) {
                                outletName = outletsData[outletId].name;
                                break;
                            }
                         }
                    }


                    await AsyncStorage.setItem(
                        "email",
                        _user.email
                    );
                    await AsyncStorage.setItem(
                        "password",
                        _user.password
                    );
                    await AsyncStorage.setItem(
                        "contact",
                        _user.contact
                    );
                    await AsyncStorage.setItem(
                        "address",
                        _user.address
                    );
                    await AsyncStorage.setItem(
                        "name",
                        _user.name
                    );
                    await AsyncStorage.setItem(
                        "nic",
                        _user.nic
                    );
                    await AsyncStorage.setItem(
                        "outlet_id",
                        _user.outlet_id
                    );
                     await AsyncStorage.setItem(
                        "outlet_name",
                        outletName
                    );
                    await AsyncStorage.setItem(
                        "district",
                        _user.district
                    );

                }


                if (isValidUser) {
                    console.log('Customer Login Successfully');
                    alert('Customer Login Successfully');
                    router.push("/homepage");
                } else {
                    console.log('Invalid email or password');
                    alert('Invalid email or password');
                }
            } else {
                console.log('No consumers found');
                alert('No consumers found');
            }
        } catch (error) {
            console.error('Error retrieving data:', error);
            alert('An error occurred. Please try again later.');
        }
    };

    return (
        <View style={styles.container}>
            <ScrollView>
                <Text style={styles.title}>Login to the Account</Text>
                <TextInput
                    style={styles.input}
                    placeholder="Email Address"
                    placeholderTextColor="#888"
                    onChangeText={setEmailAddress}
                    value={email}
                />

                <View style={styles.passwordContainer}>
                    <TextInput
                        style={[styles.passwordInput]}
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

                <TouchableOpacity style={styles.button} onPress={loginCustomer}>
                    <Text style={styles.buttonText}> Login</Text>
                </TouchableOpacity>

                <TouchableOpacity style={styles.buttonContainer} onPress={() => router.push("/customerregister")}>
                    <Text style={styles.buttonContainerText}>Don't have an account? Sign Up</Text>
                </TouchableOpacity>
            </ScrollView>
        </View>
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        marginTop: 20,
        backgroundColor: '#f9f9f9',
        padding: 20,
        justifyContent: 'center',
    },
    title: {
        fontSize: 24,
        fontWeight: 'bold',
        textAlign: 'center',
        marginBottom: 20,
        color: '#000',
    },
    input: {
        backgroundColor: '#fff',
        borderWidth: 1,
        borderColor: '#ccc',
        borderRadius: 5,
        padding: 10,
        marginBottom: 15,
        color: '#000',
        fontSize: 16,
    },
    buttonContainer: {
        marginTop: 15,
        paddingVertical: 10,
    },
    buttonContainerText: {
        fontWeight: 'bold',
        textAlign: "center",
        fontSize: 16,
    },
    button: {
        backgroundColor: "#2776D1",
        paddingVertical: 10,
        borderRadius: 30,
    },
    buttonText: {
        textAlign: 'center',
        color: "white",
        fontWeight: 'bold',
        textTransform: 'uppercase',
    },
    passwordContainer: {
        flexDirection: 'row',
        alignItems: 'center',
        borderWidth: 1,
        borderColor: '#ccc',
        borderRadius: 8,
        paddingHorizontal: 12,
        backgroundColor: '#f9f9f9',
        marginBottom: 15,
    },
    passwordInput: {
        flex: 1,
        paddingVertical: 12,
    }
});