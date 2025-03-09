import React, { useState } from 'react';
import { Link } from 'react-router-dom';

function RegisterPage() {
  // ... existing code ...
  
  return (
    <div className="register-page">
      {/* ... existing form elements ... */}
      
      {/* Add this before the closing </div> */}
      <p className="mt-3 text-center">
        Already have an account? <Link to="/login">Login here</Link>
      </p>
      
    </div>
  );
}

export default RegisterPage; 