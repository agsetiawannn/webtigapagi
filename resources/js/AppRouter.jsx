import React from 'react';
import { Routes, Route, useLocation } from 'react-router-dom';
import Layout from './components/Layout';
import Loader from './components/Loader';
import Home from './pages/Home';
import Work from './pages/Work';
import WorkDetail from './pages/WorkDetail';
import Team from './pages/Team';
import Clients from './pages/Clients';

function App() {
  const location = useLocation();

  return (
    <>
      <Loader />
      <Routes location={location} key={location.pathname}>
        <Route path="/" element={<Layout />}>
          <Route index element={<Home />} />
          <Route path="work" element={<Work />} />
          <Route path="work/:slug" element={<WorkDetail />} />
          <Route path="team" element={<Team />} />
          <Route path="clients" element={<Clients />} />
        </Route>
      </Routes>
    </>
  );
}

export default App;
