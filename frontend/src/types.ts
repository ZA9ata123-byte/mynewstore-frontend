// This file will contain all common TypeScript types for the project.

/**
 * Represents the structure of a paginated API response from Laravel.
 */
export interface Paginator<T> {
  data: T[];
  current_page: number;
  first_page_url: string;
  from: number;
  last_page: number;
  last_page_url: string;
  links: {
    url: string | null;
    label: string;
    active: boolean;
  }[];
  next_page_url: string | null;
  path: string;
  per_page: number;
  prev_page_url: string | null;
  to: number;
  total: number;
}

/**
 * Represents a Category.
 */
export interface Category {
  id: number;
  name: string;
  slug: string;
  // Add any other category properties you have, e.g., description
}

/**
 * Represents a Product Image.
 */
export interface ProductImage {
  id: number;
  image_url: string;
  is_featured: boolean;
}

/**
 * Represents a Product.
 */
export interface Product {
    id: number;
    name: string;
    slug: string;
    description: string;
    short_description: string;
    price: number;
    stock: number;
    total_stock: number;
    price_range: string;
    product_type: 'simple' | 'variable';
    category: Category;
    images: ProductImage[];
    // Add other properties like variants if needed
}

export interface ProductVariant {
  id: number;
  name: string;
  price: number;
  stock: number;
  total_stock: number;
  product_id: number;
  // Add other properties like options if needed
}