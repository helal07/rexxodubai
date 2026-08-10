import React from 'react';
import { Head } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';

interface PageData {
    id: number;
    title: string;
    slug: string;
    content: string;
    meta_title?: string;
    meta_description?: string;
}

interface PageProps {
    page: PageData;
}

export default function Page({ page }: PageProps) {
    return (
        <AppLayout>
            <Head>
                <title>{page.meta_title || page.title}</title>
                {page.meta_description && (
                    <meta name="description" content={page.meta_description} />
                )}
            </Head>

            <div className="bg-[#f8fafc] min-h-screen py-16 md:py-24">
                <div className="container mx-auto px-4">
                    <div className="max-w-4xl mx-auto bg-white p-8 md:p-12 rounded-3xl shadow-sm border border-[#e2e8f0]">
                        <h1 className="text-3xl md:text-4xl font-serif font-bold text-[#0f172a] mb-8 pb-6 border-b border-[#e2e8f0]">
                            {page.title}
                        </h1>

                        <div 
                            className="prose prose-slate max-w-none prose-headings:font-serif prose-a:text-[#0284c7] hover:prose-a:text-[#0369a1] prose-img:rounded-2xl"
                            dangerouslySetInnerHTML={{ __html: page.content || '' }}
                        />
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
