import { router } from 'expo-router';
import React from 'react';
import {
  View,
  Text,
  StyleSheet,
  TouchableOpacity,
  Image,
} from 'react-native';

const SuccessMessage: React.FC = () => {
  const handleClose = () => {
    // Add the logic to navigate or close the modal
    console.log('Modal closed');
  };

  return (
    <View style={styles.container}>
      <View style={styles.modal}>
        {/* Success Icon */}
        <Image
          source={{
            uri: 'https://cdn-icons-png.flaticon.com/512/845/845646.png', // Green check icon
          }}
          style={styles.icon}
        />

        {/* Success Message */}
        <Text style={styles.message}>
          Your request has been successfull. Scan your QR
        </Text>

        {/* Close Button */}
        <TouchableOpacity style={styles.closeButton} onPress={() => router.push("/homepage")}>
          <Text style={styles.closeButtonText}>View QR code</Text>
        </TouchableOpacity>
      </View>
    </View>
  );
};

export default SuccessMessage;

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: 'rgba(0, 0, 0, 0.5)', // Semi-transparent background
  },
  modal: {
    width: '85%',
    backgroundColor: '#fff',
    borderRadius: 10,
    padding: 20,
    alignItems: 'center',
    shadowColor: '#000',
    shadowOpacity: 0.3,
    shadowOffset: { width: 0, height: 2 },
    shadowRadius: 4,
    elevation: 5,
  },
  icon: {
    width: 80,
    height: 80,
    marginBottom: 20,
  },
  message: {
    fontSize: 16,
    fontWeight: '500',
    textAlign: 'center',
    color: '#333',
    marginBottom: 10,
  },
  phoneNumber: {
    fontSize: 14,
    color: '#888',
    textAlign: 'center',
    marginBottom: 20,
  },
  closeButton: {
    backgroundColor: '#007BFF',
    paddingVertical: 12,
    paddingHorizontal: 20,
    borderRadius: 8,
    width: '100%',
    alignItems: 'center',
  },
  closeButtonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: 'bold',
  },
});
