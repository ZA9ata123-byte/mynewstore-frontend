import api from '@/lib/axiosConfig';

// Interfaces
interface AdminUser {
    id: number;
    name: string;
    email: string;
}

interface AuthResponse {
    token: string;
    user: AdminUser;
    is_admin?: boolean; // <-- زدنا هادي باش نعرفو واش أدمن
}

interface LoginCredentials {
    email: string;
    password: string;
}

// --- Token Management ---
const ADMIN_TOKEN_KEY = 'admin_auth_token';

export const saveAdminToken = (token: string): void => {
    if (typeof window !== 'undefined') localStorage.setItem(ADMIN_TOKEN_KEY, token);
};

export const getAdminToken = (): string | null => {
    return typeof window !== 'undefined' ? localStorage.getItem(ADMIN_TOKEN_KEY) : null;
};

export const removeAdminToken = (): void => {
    if (typeof window !== 'undefined') localStorage.removeItem(ADMIN_TOKEN_KEY);
};

// --- API Functions ---
export const adminLogin = async (credentials: LoginCredentials): Promise<AuthResponse> => {
    try {
        // كنتصلو بنفس المسار العادي ديال الدخول
        const response = await api.post<AuthResponse>('/login', credentials);
        
        // --- كنتأكدو أن الرد فيه علامة الأدمن ---
        if (!response.data.is_admin) {
            // إلا مكنش أدمن، كنرفضو الدخول
            throw new Error('ليس لديك صلاحيات الوصول.');
        }

        // إلا كان أدمن، كنسجلو التوكن
        if (response.data.token) {
            saveAdminToken(response.data.token);
        }
        
        return response.data;

    } catch (error) {
        console.error("Admin login failed:", error);
        throw error; // كنخليو الخطأ يبان في الصفحة
    }
};

export const logoutAdmin = (): void => {
    removeAdminToken();
};