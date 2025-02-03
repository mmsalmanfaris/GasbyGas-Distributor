import React from 'react';
import {View, Text,TouchableOpacity,StyleSheet,SafeAreaView, ScrollView,BackHandler} from 'react-native';

const ActivitiesPage = ({ navigation }: { navigation: any }) => {
  const requestsData = [
    { no: '06', date: '2025.01.01', count: '02', status: 'Pending' },
    { no: '05', date: '2024.11.01', count: '02', status: 'Delivered' },
    { no: '04', date: '2024.09.01', count: '01', status: 'Delivered' },
    { no: '03', date: '2024.07.01', count: '02', status: 'Delivered' },
    { no: '02', date: '2024.06.01', count: '01', status: 'Delivered' },
    { no: '01', date: '2024.03.01', count: '03', status: 'Delivered' },
  ];

  return (
    <SafeAreaView style={{ flex: 1, backgroundColor: '#fff' }}>
      <View style={styles.header}>
        <TouchableOpacity onPress={() => navigation.goBack()}>
          <Text style={styles.backText}>&lt; Back</Text>
        </TouchableOpacity>
        <Text style={styles.headerTitle}>Activities</Text>
      </View>

      <ScrollView contentContainerStyle={styles.container}>
        <Text style={styles.title}>Total Requests</Text>
        <View style={styles.table}>
          <View style={[styles.row, styles.headerRow]}>
            <Text style={[styles.cell, styles.headerCell]}>No</Text>
            <Text style={[styles.cell, styles.headerCell]}>Date</Text>
            <Text style={[styles.cell, styles.headerCell]}>Count</Text>
            <Text style={[styles.cell, styles.headerCell]}>Status</Text>
          </View>
          {requestsData.map((request, index) => (
            <View
              key={index}
              style={[styles.row, index % 2 === 0 && styles.evenRow]}
            >
              <Text style={styles.cell}>{request.no}</Text>
              <Text style={styles.cell}>{request.date}</Text>
              <Text style={styles.cell}>{request.count}</Text>
              <Text style={styles.cell}>{request.status}</Text>
            </View>
          ))}
        </View>
      </ScrollView>

      <TouchableOpacity
        style={styles.backButton}
        onPress={() => navigation.goBack()}
      >
        <Text style={styles.backButtonText}>Back</Text>
      </TouchableOpacity>
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  header: {
    flexDirection: 'row',
    alignItems: 'center',
    padding: 16,
    backgroundColor: '#f5f5f5',
    borderBottomWidth: 1,
    borderBottomColor: '#ddd',
  },
  backText: {
    fontSize: 16,
    color: '#007bff',
  },
  headerTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    marginLeft: 16,
    color: '#000',
  },
  container: {
    padding: 16,
  },
  title: {
    fontSize: 18,
    fontWeight: 'bold',
    marginBottom: 16,
  },
  table: {
    borderWidth: 1,
    borderColor: '#ddd',
    borderRadius: 8,
    overflow: 'hidden',
  },
  row: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingVertical: 8,
    paddingHorizontal: 4,
  },
  headerRow: {
    backgroundColor: '#007bff',
  },
  evenRow: {
    backgroundColor: '#f9f9f9',
  },
  cell: {
    flex: 1,
    textAlign: 'center',
    fontSize: 14,
    paddingVertical: 4,
  },
  headerCell: {
    color: '#fff',
    fontWeight: 'bold',
  },
  backButton: {
    backgroundColor: '#007bff',
    paddingVertical: 12,
    margin: 16,
    borderRadius: 8,
    alignItems: 'center',
  },
  backButtonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: 'bold',
  },
});

export default ActivitiesPage;
