import axios from 'axios';
import { toast } from 'react-toastify';

const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 422) {
      const errors = error.response.data.errors;
      if (errors) {
        Object.values(errors).flat().forEach((message) => {
          toast.error(message);
        });
      } else if (error.response.data.message) {
        toast.error(error.response.data.message);
      }
    }
    return Promise.reject(error);
  }
);

export default api;
