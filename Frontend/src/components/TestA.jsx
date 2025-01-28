import { useEffect, useState } from 'react';
import Echo from 'laravel-echo';
import { Link } from 'react-router-dom';
import { fetchAnalytics, fetchNewOrder, fetchRecommendations } from './api';
import Pusher from 'pusher-js';

const TestA = () => {
  const [analytics, setAnalytics] = useState({});
  const [newOrder, setNewOrder] = useState(null);
  const [recommendations, setRecommendations] = useState('');
  const [message, setMessage] = useState('');
  const fetchData = async () => {
    try {
      const analyticsData = await fetchAnalytics();
      setAnalytics(analyticsData);
      const recommendationsData = await fetchRecommendations();
      setRecommendations(recommendationsData);
      const newOrderData = await fetchNewOrder();
      setNewOrder(newOrderData);
    } catch (error) {
      setMessage('Error fetching data',error);
    }
  };

  useEffect(() => {
    fetchData();
    window.Echo = new Echo({
      broadcaster: 'pusher',
      key: import.meta.env.VITE_PUSHER_APP_KEY,
      cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
      forceTLS: true,
    });

    const channel = window.Echo.channel('orders');
    channel.listen('App\\Events\\OrderCreated', (data) => {
      console.log('Received Order Created Event:', data);
      // Update the UI with the new order and analytics data
      setNewOrder(data.order);
      setAnalytics(data.analytics);
      setRecommendations(data.recommendations);
    });

    // Cleanup when the component unmounts
    return () => {
      window.Echo.leave('orders');
    };
  }, []); // Empty dependency array, so this runs only once after the component mounts

  return (
    <div className="app-container">
      <h1 className="title">Real-Time Sales Analytics</h1>
      <div className="links">
        <Link to="/order" className="link">Create Order</Link>
      </div>
      {message && <p className="message error">{message}</p>}

      <div className="analytics-section">
        <h2>Real-Time Analytics</h2>
        <div className="analytics-data">
          <p>Total Revenue: ${analytics.total_revenue}</p>
          <p>Orders in Last Minute: {analytics.order_count}</p>
          <p>Revenue in Last Minute: ${analytics.recent_revenue}</p>
        </div>
        <h3>Top Products</h3>
        <ul className="top-products">
          {analytics.top_products?.map((product, idx) => (
            <li key={idx} className="top-product">
              {product.product_name}: {product.total_sales}
            </li>
          ))}
        </ul>
      </div>

      <div className="recommendations-section">
        <h2>Promotional Recommendations</h2>
        <p>{recommendations || 'Loading recommendations...'}</p>
      </div>

      {newOrder && (
        <div className="new-order-notification">
          <h3>Last Order:</h3>
          <p>Product Name: {newOrder.product.name}</p>
          <p>Quantity: {newOrder.quantity}</p>
          <p>Price: ${newOrder.price}</p>
        </div>
      )}
    </div>
  );
};

export default TestA;
