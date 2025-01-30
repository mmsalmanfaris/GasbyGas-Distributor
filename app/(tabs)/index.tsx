import { Image, StyleSheet, View, Text, TextInput, TouchableOpacity, Platform, Button, ScrollView, Linking } from 'react-native';

import { HelloWave } from '@/components/HelloWave';
import ParallaxScrollView from '@/components/ParallaxScrollView';
import { ThemedText } from '@/components/ThemedText';
import { ThemedView } from '@/components/ThemedView';
import { NavigationContainer } from '@react-navigation/native';
import { createStaticNavigation } from '@react-navigation/native'; 
import { useNavigation } from '@react-navigation/native';


import React, { useState } from 'react';

export default function CreateAccountScreen() {
  const [photo, setPhoto] = useState(null);

  const uploadPhoto = () => {
    // Logic for uploading photo (use any library like react-native-image-picker)
    console.log('Upload Photo Button Pressed');
  };

  return (
    <View style={styles.container}>
        <ScrollView>
      <Text style={styles.title}>Create a New Account</Text>

      <TouchableOpacity style={styles.photoContainer} onPress={uploadPhoto}>
        {photo ? (
          <Image source={{ uri: photo }} style={styles.photo} />
        ) : (
          <View style={styles.photoPlaceholder}>
            <Text style={styles.photoText}>Upload Photo</Text>
          </View>
        )}
      </TouchableOpacity>
   
     <TextInput
        style={styles.input}
        placeholder="Full Name"
        placeholderTextColor="#888"
      />
      <TextInput
        style={styles.input}
        placeholder="Email Address"
        placeholderTextColor="#888"
        keyboardType="email-address"
      />
      <TextInput
        style={styles.input}
        placeholder="NIC Number"
        placeholderTextColor="#888"
        keyboardType="numeric"
      />
      <TextInput
        style={styles.input}
        placeholder="Phone number"
        placeholderTextColor="#888"
        keyboardType="phone-pad"
      />
      <TextInput
        style={styles.input}
        placeholder="Address"
        placeholderTextColor="#888"
      />
      
      <TextInput
        style={styles.input}
        placeholder="City"
        placeholderTextColor="#888"
        keyboardType="email-address"
      />
      <TextInput
        style={styles.input}
        placeholder="Shop Name"
        placeholderTextColor="#888"
        keyboardType="email-address"
      />
      <TextInput
        style={styles.input}
        placeholder="Certificate"
        placeholderTextColor="#888"
        keyboardType="email-address"
      />
      <TextInput
        style={styles.input}
        placeholder="Password"
        placeholderTextColor="#888"
        keyboardType="email-address"
      />
      <Button 
        title='Submit'
        
      />
      </ ScrollView> 
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    marginTop:20,
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
  photoContainer: {
    alignSelf: 'center',
    width: 100,
    height: 100,
    borderRadius: 50,
    backgroundColor: '#e0e0e0',
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: 20,
  },
  photoPlaceholder: {
    justifyContent: 'center',
    alignItems: 'center',
  },
  photo: {
    width: 100,
    height: 100,
    borderRadius: 50,
  },
  photoText: {
    fontSize: 14,
    color: '#aaa',
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
  
});


