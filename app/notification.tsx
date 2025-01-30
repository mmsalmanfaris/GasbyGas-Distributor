import React, { useState } from 'react';
import {
  View,
  Text,
  TouchableOpacity,
  FlatList,
  StyleSheet,
  SafeAreaView,
  ScrollView,
} from 'react-native';

const NotificationPage = () => {
  const [activeTab, setActiveTab] = useState('Notifications');
  {/*this data come from the last requests from the db*/}
  const data = [
    {
      id: '1',
      date: '2025.01.01',
      message:
        'Your order confirmed. Ref: 00013331237\n2025.01.01 13:57:28. We send you a QR code to your email. Please verify that when you buy the gas.',
    },
    {
      id: '2',
      date: '2024.11.01',
      message:
        'Your order confirmed. Ref: 00013330924\n2024.11.01 09:32:08. We send you a QR code to your email. Please verify that when you buy the gas.',
    },
    {
      id: '3',
      date: '2025.01.01',
      message:
        'Your order confirmed. Ref: 00013331237\n2025.01.01 13:57:28. We send you a QR code to your email. Please verify that when you buy the gas.',
    },
    {
      id: '4',
      date: '2025.01.01',
      message:
        'Your order confirmed. Ref: 00013331237\n2025.01.01 13:57:28. We send you a QR code to your email. Please verify that when you buy the gas.',
    },
    {
      id: '5',
      date: '2025.01.01',
      message:
        'Your order confirmed. Ref: 00013331237\n2025.01.01 13:57:28. We send you a QR code to your email. Please verify that when you buy the gas.',
    },
  ];

  const renderNotification = ({ item }: { item: any }) => (
    <View style={styles.notificationCard}>
      <Text style={styles.dateText}>{item.date}</Text>
      <Text style={styles.messageText}>{item.message}</Text>
    </View>   
  );

  return (
    <SafeAreaView style={{ flex: 1, backgroundColor: '#fff' }}>
      <View style={styles.header}>
         
        <TouchableOpacity
          style={[
            styles.tabButton,
            activeTab === 'Notifications' && styles.activeTabButton,
          ]}
          onPress={() => setActiveTab('Notifications')}
        >
          <Text
            style={[
              styles.tabText,
              activeTab === 'Notifications' && styles.activeTabText,
            ]}
          >
            Notifications
          </Text>
        </TouchableOpacity>
        <TouchableOpacity
          style={[
            styles.tabButton,
            activeTab === 'Activities' && styles.activeTabButton,
          ]}
          onPress={() => setActiveTab('Activities')}
        >
          <Text
            style={[
              styles.tabText,
              activeTab === 'Activities' && styles.activeTabText,
            ]}
          >
            Activities
          </Text>
        </TouchableOpacity>
        <TouchableOpacity
          style={[
            styles.tabButton,
            activeTab === 'My QRs' && styles.activeTabButton,
          ]}
          onPress={() => setActiveTab('My QRs')}
        >
          <Text
            style={[
              styles.tabText,
              activeTab === 'My QRs' && styles.activeTabText,
            ]}
          >
            My QRs
          </Text>
        </TouchableOpacity>
      </View>
      

      {activeTab === 'Notifications' && (
        <FlatList
          data={data}
          keyExtractor={(item) => item.id}
          renderItem={renderNotification}
          contentContainerStyle={{ paddingHorizontal: 16, paddingTop: 8 }}
        />
      )}

      {activeTab === 'Activities' && (
        <View style={styles.centeredContainer}>
          <Text style={styles.placeholderText}>No Activities Found.</Text>
        </View>
      )}

      {activeTab === 'My QRs' && (
        <View style={styles.centeredContainer}>
          <Text style={styles.placeholderText}>No QR Codes Found.</Text>
        </View>
      )}
     
      
    </SafeAreaView>
    
  );
};

const styles = StyleSheet.create({
  header: {
    flexDirection: 'row',
    justifyContent: 'space-around',
    backgroundColor: '#f5f5f5',
    paddingVertical: 25,
    borderBottomWidth: 3,
    borderBottomColor: '#ddd',
  },
  tabButton: {
    paddingVertical: 12,
    paddingHorizontal: 16,
    borderRadius: 8,
  },
  activeTabButton: {
    backgroundColor: '#007bff',
  },
  tabText: {
    fontSize: 16,
    color: '#000',
    textAlign: 'center',
  },
  activeTabText: {
    color: '#fff',
    fontWeight: 'bold',
  },
  notificationCard: {
    backgroundColor: '#f9f9f9',
    borderWidth: 1,
    borderColor: '#ddd',
    borderRadius: 8,
    padding: 12,
    marginBottom: 12,
  },
  dateText: {
    fontSize: 14,
    fontWeight: 'bold',
    marginBottom: 4,
  },
  messageText: {
    fontSize: 14,
    color: '#555',
  },
  icon: {
    flex: 1,
    backgroundColor: '#fff',
    padding: 15,
  },
  centeredContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
  placeholderText: {
    fontSize: 18,
    color: '#888',
  },
});

export default NotificationPage;
