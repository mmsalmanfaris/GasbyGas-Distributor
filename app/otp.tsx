import { router } from 'expo-router';
import React, { useState, useRef } from 'react';
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  StyleSheet,
  Alert
} from 'react-native';

const OTPScreen: React.FC = () => {
  const [otp, setOtp] = useState<string[]>(['', '', '', '']);
  const inputs = useRef<TextInput[]>([]);

  const handleOtpChange = (value: string, index: number) => {
    const updatedOtp = [...otp];
    updatedOtp[index] = value;
    setOtp(updatedOtp);

    // Automatically focus next input
    if (value && index < otp.length - 1) {
      inputs.current[index + 1]?.focus();
    }

    // Automatically focus previous input if cleared
    if (!value && index > 0) {
      inputs.current[index - 1]?.focus();
    }
  };

  const handleResend = () => {
    Alert.alert('OTP Resent', 'A new OTP has been sent to your phone number.');
  };

  const handleSubmit = () => {
    const enteredOtp = otp.join('');
    if (enteredOtp.length < otp.length) {
      Alert.alert('Error', 'Please enter the full OTP.');
      return;
    }
    Alert.alert('OTP Submitted', `Entered OTP: ${enteredOtp}`);
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>We sent an OTP to your phone number</Text>
      <Text style={styles.phoneNumber}>+94 74******21</Text>
      <Text style={styles.subtitle}>Enter your OTP here.</Text>

      <View style={styles.otpContainer}>
        {otp.map((digit, index) => (
          <TextInput
            key={index}
            style={styles.otpInput}
            value={digit}
            onChangeText={(value) => handleOtpChange(value, index)}
            keyboardType="numeric"
            maxLength={1}
            ref={(input) => (inputs.current[index] = input!)}
            placeholder="•"
          />
        ))}
      </View>

      <View style={styles.resendContainer}>
        <Text style={styles.resendText}>Didn't receive OTP?</Text>
        <TouchableOpacity onPress={handleResend}>
          <Text style={styles.resendButton}> Resend now!</Text>
        </TouchableOpacity>
      </View>

      <TouchableOpacity style={styles.submitButton} 
      onPress={() => router.push("/homepage")}><Text style={styles.submitButtonText}>Submit</Text>
      </TouchableOpacity>
    </View>
  );
};

export default OTPScreen;

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#fff',
    paddingHorizontal: 20,
  },
  title: {
    fontSize: 16,
    color: '#555',
    textAlign: 'center',
    marginBottom: 5,
  },
  phoneNumber: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#333',
    marginBottom: 20,
  },
  subtitle: {
    fontSize: 16,
    color: '#777',
    marginBottom: 20,
  },
  otpContainer: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 20,
  },
  otpInput: {
    width: 50,
    height: 50,
    borderWidth: 1,
    borderColor: '#ccc',
    textAlign: 'center',
    fontSize: 18,
    borderRadius: 5,
    marginHorizontal: 5,
  },
  resendContainer: {
    flexDirection: 'row',
    marginBottom: 20,
  },
  resendText: {
    fontSize: 14,
    color: '#777',
  },
  resendButton: {
    fontSize: 14,
    color: '#007BFF',
    fontWeight: 'bold',
  },
  submitButton: {
    backgroundColor: '#007BFF',
    paddingVertical: 15,
    paddingHorizontal: 100,
    borderRadius: 8,
  },
  submitButtonText: {
    color: '#fff',
    fontSize: 18,
    fontWeight: 'bold',
  },
});
