import axios from 'axios';

const API_URL = 'http://localhost:8000/api';

export const fetchAnalytics = async () => {
  try {
    const { data } = await axios.get(`${API_URL}/get`);
    return data;
  } catch (error) {
    console.error('Error fetching analytics:', error);
  }
};

export const fetchRecommendations = async () => {
  try {
    const { data } = await axios.get(`${API_URL}/recom`);
    return data.recommendations;
  } catch (error) {
    console.error( error);
  }
};

export const fetchNewOrder=async()=>{
  try {
    const res=await axios.get(`${API_URL}/latest-order`);
    return res.data;
  }catch(error){
    console.error(error);
  }
}


 
export const fetchAddOrder=async(orderData)=>{
  try{
      
      const formData = new FormData();
  
      for (const [key, value] of Object.entries(orderData)) {
      formData.append(key, value);
      }
      const res=await axios.post(API_URL, formData,{
          headers: {
              'Accept': 'application/json',
              'Content-Type': 'multipart/form-data',
          },
          withCredentials: true, // Required for Laravel Sanctum

      });
      return res.data;
  } catch (error) {
      console.error('Error creating product:', error.response ? error.response.data : error.message);
      throw error; // Rethrow to handle in the calling component
  }
};
