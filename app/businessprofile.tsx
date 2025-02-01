import { StyleSheet, View, Text, TextInput, TouchableOpacity, ScrollView, Image,} from 'react-native';
import React, { useState, useEffect } from 'react';
import { router } from 'expo-router';
import { Ionicons as Icon } from "@expo/vector-icons";
import { ref, get } from 'firebase/database';
import { database } from "../db/DBConfig";
import { getAuth } from 'firebase/auth';
import AsyncStorage from '@react-native-async-storage/async-storage';

const auth = getAuth();

export { auth, database };

const PersonalSettingsPage: React.FC = () => {
  const [name, setFullName] = useState('');
  const [email, setEmailAddress] = useState('');
  const [contact, setContact] = useState('');
  const [nic, setNICnumber] = useState('');
  const [address, setAddress] = useState('');
  const [district, setDistrict] = useState('');
  const [outlet_id, setOutlet] = useState('');  
  const [rnumber, setrnumber] = useState('');
  const [password, setPassword] = useState('');
  const [isPasswordVisible, setPasswordVisible] = useState(false);

  useEffect(() => {
      const fetchUserData = async () => {
          try {
              const _email = await AsyncStorage.getItem('email');
              const _outlet_name = await AsyncStorage.getItem('outlet_name');
              const _address = await AsyncStorage.getItem('address');
              const _name = await AsyncStorage.getItem('name');
              const _password = await AsyncStorage.getItem('password');
              const _contact = await AsyncStorage.getItem('contact');
              const _nic = await AsyncStorage.getItem('nic');
              const _district = await AsyncStorage.getItem('district');              
              const _rnumber = await AsyncStorage.getItem('rnumber');

              setFullName(_name || '');
              setEmailAddress(_email || '');
              setAddress(_address || '');
              setPassword(_password || '');
              setNICnumber(_nic || '');
              setDistrict(_district || '');
              setOutlet(_outlet_name || '');
              setContact(_contact || '');
              setrnumber(_rnumber || '');


              const user = auth.currentUser;
              if (user) {
                  // Fetch the user data from the 'consumers' node
                  const consumersRef = ref(database, 'consumers');
                  const snapshot = await get(consumersRef);
                  if (snapshot.exists()) {
                      const consumersData = snapshot.val();
                      // Iterate through the consumers to find the matching user by email
                      for (const consumerId in consumersData) {
                          if (consumersData[consumerId].email === user.email) {
                              const userData = consumersData[consumerId];
                              break;
                          }
                      }
                  } else {
                      console.log("User data not found.");
                  }
              } else {
                  console.log("No user is logged in.");
                  router.push("/businessprofile");
              }
          } catch (error) {
              console.error("Error fetching user data:", error);
          }
      };
      fetchUserData();
  }, []);

  const togglePasswordVisibility = () => {
      setPasswordVisible(!isPasswordVisible);
  };

  return (
      <ScrollView contentContainerStyle={{ flexGrow: 1 }}>
          <View style={styles.container}>
              <View style={styles.header}>
                  <TouchableOpacity onPress={() => router.push("/businesshomepage")}>
                      <Icon name="arrow-back" size={24} color="#000" style={styles.container} />
                  </TouchableOpacity>
                  <Text style={styles.title}>Personal Settings</Text>
                  <TouchableOpacity>
                      <Icon name="settings-outline" size={24} color="#000" style={styles.container} />
                  </TouchableOpacity>
              </View>
              <View style={styles.profileContainer}>
                  <Image
                      source={require('../assets/images/prfl.png')}
                      style={styles.profileImage}
                  />
              </View>
              <Text style={styles.name}>{name}</Text>
              <Text style={styles.email}>{email}</Text>

              <View style={styles.inputContainer}>
                  <TextInput
                      style={styles.input}
                      value={name}
                      onChangeText={setFullName}
                      placeholder="Full Name"
                  />
                  <TextInput
                      style={styles.input}
                      value={email}
                      onChangeText={setEmailAddress}
                      placeholder="Email Address"
                  />
                  <TextInput
                      style={styles.input}
                      value={contact}
                      onChangeText={setContact}
                      placeholder="Phone Number"
                  />
                  <TextInput
                      style={styles.input}
                      value={nic}
                      onChangeText={setNICnumber}
                      placeholder="NIC/ID"
                  />
                  <TextInput
                      style={styles.input}
                      value={address}
                      onChangeText={setAddress}
                      placeholder="Address"
                  />
                  <TextInput
                      style={styles.input}
                      value={district}
                      onChangeText={setDistrict}
                      placeholder="District"
                  />
                  <TextInput
                      style={styles.input}
                      value={outlet_id}
                      placeholder="Outlet"
                  />
                  <TextInput
                      style={styles.input}
                      value={rnumber}
                      onChangeText={setrnumber}
                      placeholder="Registration Number"
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
              </View>

              <TouchableOpacity style={styles.saveButton}>
                  <Text style={styles.saveButtonText}>Update</Text>
              </TouchableOpacity>

              <TouchableOpacity style={styles.LogoutButton} onPress={() => router.push("/login")}>
                  <Text style={styles.LogoutButtonText}>Logout</Text>
              </TouchableOpacity>
          </View>
      </ScrollView>
  );
};

export default PersonalSettingsPage;

const styles = StyleSheet.create({
  container: {
      flex: 1,
      backgroundColor: '#fff',
      padding: 15,
  },
  header: {
      flexDirection: 'row',
      justifyContent: 'space-between',
      alignItems: 'center',
      marginBottom: 10,
  },
  title: {
      fontSize: 18,
      fontWeight: 'bold',
  },
  profileContainer: {
      alignItems: 'center',
      marginBottom: 20,
  },
  profileImage: {
      width: 100,
      height: 100,
      borderRadius: 50,
      borderWidth: 2,
      borderColor: '#007BFF',
  },
  editIcon: {
      position: 'absolute',
      bottom: 5,
      right: 5,
      backgroundColor: '#007BFF',
      borderRadius: 15,
      padding: 5,
  },
  name: {
      fontSize: 16,
      fontWeight: 'bold',
      textAlign: 'center',
  },
  email: {
      fontSize: 14,
      color: '#888',
      textAlign: 'center',
      marginBottom: 20,
  },
  inputContainer: {
      marginBottom: 20,
  },
  input: {
      borderWidth: 1,
      borderColor: '#ccc',
      borderRadius: 8,
      padding: 12,
      fontSize: 14,
      marginBottom: 12,
      backgroundColor: '#f9f9f9',
  },
  passwordContainer: {
      flexDirection: 'row',
      alignItems: 'center',
      borderWidth: 1,
      borderColor: '#ccc',
      borderRadius: 8,
      paddingHorizontal: 12,
      backgroundColor: '#f9f9f9',
  },
  passwordInput: {
      flex: 1,
      paddingVertical: 12,

  },
  saveButton: {
      backgroundColor: '#007BFF',
      paddingVertical: 12,
      borderRadius: 8,
      alignItems: 'center',
      marginBottom: 12
  },
  saveButtonText: {
      color: '#fff',
      fontSize: 16,
      fontWeight: 'bold',
  },
  LogoutButtonText: {
      color: '#fff',
      fontSize: 16,
      fontWeight: 'bold',
  },
  LogoutButton: {
      backgroundColor: '#000000',
      paddingVertical: 10,
      borderRadius: 8,
      alignItems: 'center',
      marginBottom: 12
  }
});