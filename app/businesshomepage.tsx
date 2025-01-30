import React from 'react';
import { View, Text, TouchableOpacity, Image, StyleSheet, Alert } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { AntDesign } from '@expo/vector-icons';
import { router } from 'expo-router';


const HomePageCustomer: React.FC = () => {
  const handleQrCodeScan = () => {
    Alert.alert('QR Code', 'This feature is not yet implemented.');
  };

  const handleGasRequest = () => {
    Alert.alert('Gas Request', 'Your gas request has been submitted.');
  };
  

  return (
    <View style={styles.container}>
      <View style={styles.header}>
        <View>
          <Ionicons name="menu" size={24} color="black" onPress={()=> router.push("/notification")} />
        </View>
        
      <TouchableOpacity style={styles.profileImage} onPress={()=> router.push("/businessprofile")}>
        <Image
          source={require('../assets/images/prfl.png') 
          }
          style={styles.profileImage}
        />
      </TouchableOpacity>
      </View>

      {/* Upcoming Delivery */}
      <View style={styles.upcomingDeliveryContainer}>
        <Text style={styles.upcomingDeliveryTitle}>Upcoming Delivery</Text>
        <Text style={styles.upcomingDeliveryDate}>2025.01.11</Text>
      </View>

      {/* Request Info */}
      <View style={styles.requestInfoContainer}>
        <View style={styles.infoBox}>
          <Text style={styles.infoTitle}>Recent Request</Text>
          <Text style={styles.infoValue}>02.01.2025</Text> {/*here the recent request date want to retrieve from the databse*/}
        </View>
        <View style={styles.infoBox}>
          <Text style={styles.infoTitle}>Total Request</Text>
          <Text style={styles.infoValue}>11</Text> {/*here the total request date want to retrieve from the databse*/}
        </View>
      </View>

      {/* QR Code */}
      <TouchableOpacity style={styles.qrCodeContainer} onPress={handleQrCodeScan}>
        <Image
          source={require('../assets/images/qr.png')}
          style={styles.qrCodeImage}
        />
        <Text style={styles.qrCodeText}>Scan Me</Text>
      </TouchableOpacity>
      
      <TouchableOpacity style={styles.requestButton} onPress={()=> router.push("/brequest")}>
        <AntDesign name="pluscircleo" size={20} color="white" />
        <TouchableOpacity style={styles.requestButtonText} onPress={() => router.push("/brequest")}>
        <Text style={styles.requestButtonText}> Request to Gas</Text></TouchableOpacity>
      </TouchableOpacity>
    </View>
  );
};

export default HomePageCustomer;

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
    paddingHorizontal: 20,
    paddingTop: 40,
  },
  header: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 20,
  },
  iconBox: {
    width: 40,
    height: 40,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#f0f0f0',
    borderRadius: 10,
  },
  profileImage: {
    width: 40,
    height: 40,
    borderRadius: 20,
  },
  upcomingDeliveryContainer: {
    alignItems: 'center',
    padding: 15,
    borderWidth: 1,
    borderColor: '#000000',
    borderRadius: 10,
    marginBottom: 20,
  },
  upcomingDeliveryTitle: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#333',
  },
  upcomingDeliveryDate: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#007BFF',
    marginTop: 5,
  },
  requestInfoContainer: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 20,
    
  },
  infoBox: {
    flex: 1,
    alignItems: 'center',
    padding: 15,
    borderWidth: 1,
    borderColor: '#000000',
    borderRadius: 10,
    marginHorizontal: 5,
  },
  infoTitle: {
    fontSize: 16,
    color: '#555',
  },
  infoValue: {
    fontSize: 24,
    color: '#333',
    marginTop: 5,
  },
  qrCodeContainer: {
    alignItems: 'center',
    marginBottom: 20,
    padding: 10,
    borderWidth: 1,
    borderColor: '#000000',
    borderRadius: 10,
  },
  qrCodeImage: {
    width: 150,
    height: 150,
    marginBottom: 10,
  },
  qrCodeText: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#555',
  },
  requestButton: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: '#007BFF',
    paddingVertical: 10,
    borderRadius: 8,
    
  },
  requestButtonText: {
    color: '#fff',
    fontSize: 18,
    fontWeight: 'bold',
    marginLeft: 10,
  },
});
