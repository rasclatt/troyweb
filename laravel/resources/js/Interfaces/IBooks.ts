export interface IBook
{
    id: string;
    title: string;
    author: string;
    description: string;
    cover_image: string;
    publisher: string;
    category: string;
    isbn: string;
    page_count: number;
    publication_date: string;
    created_at: string;
    updated_at: string;
    average_rating?: number;
    other_attributes?: {
        role?: string;
        role_id?: string;
    };
}