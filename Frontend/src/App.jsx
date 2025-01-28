import { Route, Routes } from 'react-router-dom'
import MainApp from './components/MainApp'
import Test from './Test'
import TestA from './components/TestA'

function App() {

  
  return (
    <div>
      <Routes>
        <Route path="/" element={<MainApp />} />
        <Route path='order' element={<Test />} />
        <Route path='test' element={<TestA />} />
      </Routes>
    </div>
  )
}

export default App