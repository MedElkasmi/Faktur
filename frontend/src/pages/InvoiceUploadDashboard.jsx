import React, { useState } from "react";
import axiosInstance from "../axiosConfig";

const InvoiceUploadDashboard = () => {
    const [file, setFile] = useState(null); // State to store the uploaded file
    const [extractedText, setExtractedText] = useState(""); // State to store extracted text
    const [loading, setLoading] = useState(false); // State for loading indicator
    const [error, setError] = useState(""); // State for error messages

    // Handler for file input change
    const handleFileChange = (e) => {
        setFile(e.target.files[0]);
    };

    // Handler for form submission
    const handleSubmit = async (e) => {
        e.preventDefault();
        if (!file) {
            setError("Please upload a file.");
            return;
        }

        const formData = new FormData();
        formData.append("file_path", file); // Append the file to the form data

        try {
            setLoading(true); // Show loading indicator
            setError(""); // Clear any previous errors

            // Make an API request to your backend endpoint to process the invoice
            const response = await axiosInstance.post(
                "/invoice/upload",
                formData
            );

            setExtractedText(response.data.data); // Set extracted text from response
        } catch (err) {
            setError("An error occurred while processing the invoice.");
        } finally {
            setLoading(false); // Hide loading indicator
        }
    };

    // Inline styles object
    const styles = {
        container: {
            maxWidth: "600px",
            margin: "0 auto",
            padding: "20px",
            border: "1px solid #ccc",
            borderRadius: "8px",
            textAlign: "center",
        },
        form: {
            display: "flex",
            flexDirection: "column",
            alignItems: "center",
            gap: "10px",
        },
        error: {
            color: "red",
        },
        extractedTextContainer: {
            marginTop: "20px",
            padding: "10px",
            backgroundColor: "#f9f9f9",
            border: "1px solid #ddd",
            borderRadius: "4px",
        },
    };

    return (
        <div style={styles.container}>
            <h2>Invoice Upload Dashboard</h2>

            {/* Form to upload invoice */}
            <form onSubmit={handleSubmit} style={styles.form}>
                <input
                    type="file"
                    onChange={handleFileChange}
                    accept=".jpeg,.png,.pdf"
                />
                <button type="submit" disabled={loading}>
                    Upload and Extract
                </button>
            </form>

            {/* Loading indicator */}
            {loading && <p>Loading...</p>}

            {/* Error message */}
            {error && <p style={styles.error}>{error}</p>}

            {/* Display extracted text */}
            {extractedText && (
                <div style={styles.extractedTextContainer}>
                    <h3>Extracted Text</h3>
                    <pre>{extractedText}</pre>
                </div>
            )}
        </div>
    );
};

export default InvoiceUploadDashboard;
