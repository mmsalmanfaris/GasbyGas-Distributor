import { collection, addDoc, doc, getDoc, getDocs, updateDoc, deleteDoc,} from "firebase/firestore";
  import { firestoreDB } from "./DBConfig";
  
  export const createDataInFirestore = async ({ _collection, _data }) => {
    try {
      return addDoc(collection(firestoreDB, _collection), _data)
        .then((val) => val.id)
        .catch((err) => {
          console.log(err);
          return false;
        });
      // return docRef.id;
    } catch (e) {
      console.log("error adding document : " + e);
    }
  };
  
  export const updateDataInFirestore = async ({ _collection, _data, _id }) => {
    try {
      const selectedDocRef = doc(firestoreDB, _collection, _id);
  
      return updateDoc(selectedDocRef, _data)
        .then(() => true)
        .catch((err) => {
          console.log(err);
          return false;
        });
    } catch (e) {
      console.log("error adding document : " + e);
    }
  };
  
  export const deleteDataInFirestore = async ({ _collection, _id }) => {
    try {
      const selectedDocRef = doc(firestoreDB, _collection, _id);
  
      return deleteDoc(selectedDocRef)
        .then(() => true)
        .catch((err) => {
          console.log(err);
          return false;
        });
    } catch (e) {
      console.log("error adding document : " + e);
    }
  };
  
  export const getAllDataFromFirestore = async ({ _collection }) => {
    try {
      const list = [];
      const listRef = await getDocs(collection(firestoreDB, _collection));
      listRef.forEach((doc) => {
        let obj = doc.data();
        obj._id = doc.id;
        list.push(obj);
      });
      return list;
    } catch (e) {
      console.log("error geting all data");
      console.log(e);
    }
  };
  
  export const getDataByIdFromFirestore = async ({ _collection, _id }) => {
    try {
      const docRef = doc(firestoreDB, _collection, _id);
      const docSnap = await getDoc(docRef);
  
      if (docSnap.exists()) {
        return docSnap.data();
      } else {
        return null;
      }
    } catch (e) {
      console.log("error geting all data");
      console.log(e);
    }
  };