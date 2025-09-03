import axios from 'axios';
// نستورد الدالة لي كتجيب التوكن ديال الأدمن
import { getAdminToken } from '@/services/adminAuthService';

const axiosInstance = axios.create({
    baseURL: process.env.NEXT_PUBLIC_API_URL,
    withCredentials: true,
    headers: {
        'Accept': 'application/json',
    }
});

// --- هذا هو الجزء السحري والجديد (Request Interceptor) ---
axiosInstance.interceptors.request.use(
    (config) => {
        // قبل إرسال أي طلب، قم بجلب التوكن
        const token = getAdminToken();

        // إذا كان التوكن موجوداً
        if (token) {
            // قم بإضافته إلى هيدر الـ Authorization
            config.headers.Authorization = `Bearer ${token}`;
        }
        
        return config; // قم بإرجاع الإعدادات المعدلة
    },
    (error) => {
        // في حالة وجود خطأ في إعداد الطلب
        return Promise.reject(error);
    }
);

export default axiosInstance;