'use client';

import React, { useState, useEffect } from 'react';
import { useForm } from 'react-hook-form';
import { useRouter } from 'next/navigation';
import Image from 'next/image';
import { getAdminCategories } from '@/services/adminCategoryService';
import { createProduct } from '@/services/adminProductService';
import { Category } from '@/types';

// Form data for a SIMPLE product
type SimpleProductFormData = {
  name: string;
  description: string;
  short_description: string;
  category_id: string;
  price: number;
  stock: number;
  images: FileList;
};

const StarIcon = ({ isFeatured }: { isFeatured: boolean }) => (
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" className={`w-6 h-6 cursor-pointer transition-colors ${isFeatured ? 'text-yellow-400' : 'text-gray-400 hover:text-yellow-300'}`}>
      <path fillRule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.007z" clipRule="evenodd" />
    </svg>
);

export default function NewSimpleProductPage() {
  const router = useRouter();
  const { register, handleSubmit, watch } = useForm<SimpleProductFormData>();
  const [categories, setCategories] = useState<Category[]>([]);
  const [imagePreviews, setImagePreviews] = useState<string[]>([]);
  const [featuredImageIndex, setFeaturedImageIndex] = useState<number | null>(0);
  const images = watch('images');

  useEffect(() => {
    const fetchCategories = async () => {
      try {
        const response = await getAdminCategories();
        setCategories(response.data);
      } catch (error) {
        console.error("Failed to fetch categories:", error);
      }
    };
    fetchCategories();
  }, []);

  useEffect(() => {
    if (images && images.length > 0) {
      const urls = Array.from(images).map(file => URL.createObjectURL(file));
      setImagePreviews(urls);
      return () => { urls.forEach(url => URL.revokeObjectURL(url)); };
    } else {
      setImagePreviews([]);
    }
  }, [images]);

  const onSubmit = async (data: SimpleProductFormData) => {
    const formData = new FormData();
    formData.append('name', data.name);
    formData.append('description', data.description);
    formData.append('short_description', data.short_description);
    formData.append('category_id', data.category_id);
    formData.append('price', String(data.price));
    formData.append('stock', String(data.stock));
    formData.append('product_type', 'simple'); // Set type explicitly

    if (data.images) {
      Array.from(data.images).forEach(file => { formData.append('images[]', file); });
    }
    if (featuredImageIndex !== null) {
      formData.append('featured_image_index', String(featuredImageIndex));
    }

    try {
      await createProduct(formData);
      router.push('/dashboard/products');
    } catch (error) {
      console.error('Failed to create product:', error);
      alert('Error: Could not create product.');
    }
  };

  return (
    <div className="container mx-auto p-6 bg-gray-50 min-h-screen">
      <h1 className="text-3xl font-bold text-gray-800 mb-6">Add New Simple Product</h1>
      <form onSubmit={handleSubmit(onSubmit)} className="bg-white p-8 rounded-lg shadow-md space-y-6">
        {/* Name, Category, Price, Stock, Description sections are here */}
        <div>
          <label htmlFor="name" className="block text-sm font-medium text-gray-700">Product Name</label>
          <input id="name" type="text" {...register('name', { required: true })} className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" />
        </div>
        <div>
            <label htmlFor="category_id" className="block text-sm font-medium text-gray-700">Category</label>
            <select id="category_id" {...register('category_id', { required: true })} className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                <option value="">Select a category</option>
                {categories.map(cat => (<option key={cat.id} value={cat.id}>{cat.name}</option>))}
            </select>
        </div>
        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label htmlFor="price" className="block text-sm font-medium text-gray-700">Price</label>
                <input id="price" type="number" step="0.01" {...register('price')} className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"/>
            </div>
            <div>
                <label htmlFor="stock" className="block text-sm font-medium text-gray-700">Stock</label>
                <input id="stock" type="number" {...register('stock')} className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"/>
            </div>
        </div>
        <div>
            <label htmlFor="description" className="block text-sm font-medium text-gray-700">Full Description</label>
            <textarea id="description" rows={5} {...register('description')} className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"></textarea>
        </div>
         <div>
            <label htmlFor="short_description" className="block text-sm font-medium text-gray-700">Short Description</label>
            <textarea id="short_description" rows={2} {...register('short_description')} className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"></textarea>
        </div>
        <div>
          <label className="block text-sm font-medium text-gray-700">Images</label>
          <input type="file" multiple accept="image/*" {...register('images')} className="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0" />
          <div className="mt-4 grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-4">
            {imagePreviews.map((url, index) => (
              <div key={index} className="relative group border-2 rounded-md" style={{ borderColor: featuredImageIndex === index ? '#FBBF24' : 'transparent' }}>
                <div className="relative w-full h-24">
                  <Image src={url} alt={`Preview ${index + 1}`} fill sizes="16vw" className="object-cover rounded-md" />
                </div>
                <div className="absolute top-1 right-1 bg-white/50 rounded-full p-0.5" onClick={() => setFeaturedImageIndex(index)}>
                  <StarIcon isFeatured={featuredImageIndex === index} />
                </div>
              </div>
            ))}
          </div>
        </div>
        <button type="submit" className="w-full bg-indigo-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-indigo-700">Create Simple Product</button>
      </form>
    </div>
  );
}