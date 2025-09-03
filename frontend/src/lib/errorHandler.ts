// Path: src/lib/errorHandler.ts

import { AxiosError } from "axios";

// تعريف شكل الخطأ الذي قد يأتي من الخادم
type ServerError = {
  message?: string;
  errors?: { [key: string]: string[] };
}

/**
 * هذا هو المساعد الذكي. يستقبل أي خطأ 'unknown'
 * ويقوم بتحليله بأمان ليرجع رسالة نصية واضحة.
 */
export const getErrorMessage = (error: unknown): string => {
  // الحالة الأولى: هل الخطأ هو خطأ من axios؟
  if (error instanceof AxiosError && error.response) {
    const data: ServerError = error.response.data;
    // هل يحتوي على أخطاء validation؟
    if (data.errors) {
      return Object.values(data.errors).flat().join(' ');
    }
    // هل يحتوي على رسالة خطأ عامة؟
    if (data.message) {
      return data.message;
    }
  }
  // الحالة الثانية: هل هو خطأ عادي؟
  if (error instanceof Error) {
    return error.message;
  }
  // الحالة الأخيرة: إذا لم يكن أي مما سبق
  return "حدث خطأ غير متوقع. يرجى المحاولة مرة أخرى.";
};