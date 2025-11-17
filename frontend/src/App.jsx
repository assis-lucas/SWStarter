import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import { ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import Layout from './components/Layout';
import Search from './pages/Search';
import PersonDetails from './pages/PersonDetails';
import FilmDetails from './pages/FilmDetails';
import Stats from './pages/Stats';

function App() {
  return (
    <Router>
      <Layout>
        <Routes>
          <Route path="/" element={<Search />} />
          <Route path="/person/:id" element={<PersonDetails />} />
          <Route path="/film/:id" element={<FilmDetails />} />
          <Route path="/stats" element={<Stats />} />
        </Routes>
      </Layout>
      <ToastContainer
        position="top-right"
        autoClose={5000}
        hideProgressBar={false}
        newestOnTop={false}
        closeOnClick
        rtl={false}
        pauseOnFocusLoss
        draggable
        pauseOnHover
        theme="light"
      />
    </Router>
  );
}

export default App;
