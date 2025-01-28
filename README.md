# Sales Analytics with Real-Time Order Updates

Hey there! This project is all about **real-time sales analytics** and **order updates**. It’s a **React frontend** with a **Laravel backend**. We're using **Pusher** and **Laravel Echo** to push order updates to the frontend in real-time.

---

### **Technologies**
- **Frontend:** React, Axios, Laravel Echo, Pusher
- **Backend:** Laravel, Pusher
- **Database:** MySQL (or whatever you use)

---

### **AI Help**
I used AI to help out with some bits:
- **React Code:** I got some help with handling real-time updates using Laravel Echo and Pusher. It helped me clean up some of the state management and made sure we weren’t calling unnecessary functions.
- **Laravel Setup:** Helped set up the broadcasting for when new orders are created. That includes setting up the events and making sure the backend is pushing data properly.

---

### **How I Built This (Manual Implementation)**

#### **Backend (Laravel)**

1. **Install Laravel:**
    - Run:
    ```bash
    composer create-project --prefer-dist laravel/laravel sales-analytics
    ```

2. **Install Pusher:**
    - Install Pusher for Laravel:
    ```bash
    composer require pusher/pusher-php-server
    ```
    - Add your Pusher credentials to `.env`:
    ```dotenv
    BROADCAST_DRIVER=pusher
    PUSHER_APP_ID=your-pusher-app-id
    PUSHER_APP_KEY=your-pusher-app-key
    PUSHER_APP_SECRET=your-pusher-app-secret
    PUSHER_APP_CLUSTER=your-pusher-cluster
    ```

3. **Create the `OrderCreated` Event:**
    - Make sure orders are broadcasted when they’re created. Here’s the `OrderCreated` event:
    ```php
    class OrderCreated implements ShouldBroadcast
    {
        public $order;
        
        public function __construct(Order $order)
        {
            $this->order = $order;
        }

        public function broadcastOn()
        {
            return new Channel('orders');
        }
    }
    ```

4. **Broadcasting Order:**
    - In the controller, dispatch the `OrderCreated` event whenever a new order is created:
    ```php
    event(new OrderCreated($order));
    ```

5. **Set Up Broadcasting in `EventServiceProvider`:**
    - Ensure everything is wired up in `EventServiceProvider.php`.

---

#### **Frontend (React)**

1. **Install Dependencies:**
    - Run the following to install React, Pusher, and Laravel Echo:
    ```bash
    npx create-react-app sales-analytics
    npm install --save laravel-echo pusher-js
    ```

2. **Configure Laravel Echo:**
    - Configure Echo with Pusher keys in your React component:
    ```javascript
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: process.env.REACT_APP_PUSHER_APP_KEY,
        cluster: process.env.REACT_APP_PUSHER_APP_CLUSTER,
        forceTLS: true,
    });
    ```

3. **Listen for Events:**
    - Set up the listener to get updates when an order is created:
    ```javascript
    const channel = window.Echo.channel('orders');
    channel.listen('order_created', (data) => {
        setNewOrder(data.order);
    });
    ```

4. **State Management:**
    - Keep track of the new order, analytics, and recommendations state and update the UI accordingly.

---

### **Setup and Run the Project**

Here’s how to get everything up and running:

#### **Prerequisites**
- Node.js: Download from [nodejs.org](https://nodejs.org/)
- Composer: Download from [getcomposer.org](https://getcomposer.org/)
- MySQL (or your preferred database)

#### **Steps:**

1. **Clone the repo or zip file:**
    ```bash
    cd sales-analytics
    ```

2. **Backend:**
    - Go to the backend folder and run:
    ```bash
    cd backend
    composer install
    ```
    - Create the `.env` file and fill in your Pusher and database details.
    - Run migrations:
    ```bash
    php artisan migrate
    ```

3. **Frontend:**
    - Go to the frontend folder and install dependencies:
    ```bash
    cd frontend
    npm install
    ```

4. **Start the servers:**
    - Run the backend:
    ```bash
    php artisan serve
    ```
    - Run the frontend:
    ```bash
    npm start
    ```
    - Go to [http://localhost:3000](http://localhost:3000) to check out the app.

---

### **Testing the APIs**

#### **API Test Cases**

Let’s test the backend APIs to make sure everything’s working!

**Test Case 1:** Create an Order
- **Test Method:** POST
- **Endpoint:** `/api/orderCreate`
- **Request Body:**
  ```json
  {
    "product_id": 3,
    "quantity": 2,
    "price": 20,
    "date": "2025-01-22"
  }
  ```


#### **Test Case 2:** Get Analytics

- **Test Method:** GET
- **Endpoint:** `/api/get`
- **Expected Response:**
  ```json
  {
    "total_revenue": 910,
    "top_products": [
      {
        "product_id": 3,
        "product_name": "tea",
        "total_sales": 74
      },
      {
        "product_id": 1,
        "product_name": "coffee",
        "total_sales": 12
      }
    ],
    "recent_revenue": 0,
    "order_count": 0
  }
  ```

  #### **Test Case 3:** Get Recommendations

- **Test Method:** GET
- **Endpoint:** `/api/recom`
- **Expected Response:**
  ```json
  {
    "recommendations": "No recommendations available"
  }
  ```

  #### **Test Case 4:** Get Weather

- **Test Method:** GET
- **Endpoint:** `/api/weather`
- **Expected Response:**
  ```json
  {
    "temperature": 16.42,
    "condition": "broken clouds"
  }
  ```

  ### **Real-Time Testing**

#### **Test Case:** Listen for Real-Time Order Events

1. **Step 1:** Create an order via the API (POST `/api/orderCreate`).
2. **Step 2:** Ensure that the frontend (React) updates in real-time. It should show the new order details when the `order_created` event is broadcasted.

