import {StyleSheet, View, Text, TouchableOpacity, Image, ImageBackground, Dimensions} from 'react-native';
import React from 'react';
import { router } from 'expo-router';

export default function CreateAccountScreen() {
  return (
    <ImageBackground source={require('../assets/images/gasBg.jpeg')} style={styles.background}>
      <View style={styles.container}>
        
        <Image source={require('../assets/images/GasByGasLogo.png')} style={styles.logo} />

        <TouchableOpacity
          style={styles.button}
          onPress={() => router.push("/login")}
          accessible={true}
          accessibilityLabel="Register as a business"
        >
          <Text style={styles.buttonText}>Business</Text>
        </TouchableOpacity>

        <TouchableOpacity
          style={styles.button}
          onPress={() => router.push("/customerlogin")}
          accessible={true}
          accessibilityLabel="Register as an individual"
        >
          <Text style={styles.buttonText}>Individual</Text>
        </TouchableOpacity>        
      </View>
    </ImageBackground>
  );
}

const { width } = Dimensions.get('window');

const styles = StyleSheet.create({
  background: {
    flex: 1,
    resizeMode: 'cover',
    justifyContent: 'center'
  },
  container: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center'
  },
  logo: {
    width: 300, 
    height: 60,  
    resizeMode: 'contain'
  },
  button: {
    backgroundColor: "#2776D1",
    paddingVertical: 20,
    borderRadius: 20,
    marginVertical: 10,
    width: '80%'
  },
  buttonText: {
    textAlign: 'center',
    color: "white",
    fontWeight: 'bold',
    textTransform: 'uppercase'
  }
});
