import { BrowserRouter as Router, Route, Routes } from "react-router-dom";
import InvoiceUploadDashboard from "./pages/InvoiceUploadDashboard";
import "./App.css";

function App() {
    return (
        <Router>
            <Routes>
                <Route path="/" element={<InvoiceUploadDashboard />} />
            </Routes>
        </Router>
    );
}

export default App;
