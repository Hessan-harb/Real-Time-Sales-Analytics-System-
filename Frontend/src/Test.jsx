import axios from "axios";
import { useState } from "react";

function Test() {

  const [analytics, setAnalytics] = useState({});
    const [newOrder, setNewOrder] = useState(null);
    const [message, setMessage] = useState('');

  const fetchAnalytics = async () => {
    try {
      const { data } = await axios.get('http://localhost:8000/api/get');
      setAnalytics(data);
    } catch (error) {
      console.error('Error fetching analytics:', error);
    }
  };

  const handleSendOrder = async () => {
    try {
      const newOrderData = { product_id: 1, quantity: 2, price: 10 ,date: "2025-01-22" }; // Example order data
      await axios.post('http://localhost:8000/api/createOrder', newOrderData);
      alert(`New Order Created! Product ID: ${newOrderData.price}`);

      setMessage('Order placed successfully!');
      fetchAnalytics(); // Refresh analytics data
    } catch (error) {
      console.error('Error sending order:', error);
    }
  };

  return (
    <div>
      <button onClick={handleSendOrder}>Place Order</button>
      <p>{message}</p>
    </div>
  )
}

export default Test