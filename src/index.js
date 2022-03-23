import React from 'react';
import ReactDOM from 'react-dom';
import {
  BrowserRouter as Router,
  Routes,
  Route
} from "react-router-dom";
import Show from "./Show";
import CreateNoteWizard from "./CreateNoteWizard";
import PageNotFound from "./PageNotFound";

ReactDOM.render(
  <React.StrictMode>
    <Router>
      <Routes>
        <Route path='/:code' element={<Show />} />
        <Route path='/' element={<CreateNoteWizard />} />
        <Route path="*" element={<PageNotFound />} />
      </Routes>
    </Router>
  </React.StrictMode>,
  document.getElementById('root')
);
