import React, { useRef, useState } from 'react';
import { UploadCloud, Link as LinkIcon, X, Image as ImageIcon } from 'lucide-react';

interface ImageUploaderProps {
    label: string;
    value: File | string | null;
    onChange: (val: File | string) => void;
    onRemove?: () => void;
}

export default function ImageUploader({ label, value, onChange, onRemove }: ImageUploaderProps) {
    const fileInputRef = useRef<HTMLInputElement>(null);
    const [isUrlMode, setIsUrlMode] = useState(false);
    const [urlInput, setUrlInput] = useState(typeof value === 'string' ? value : '');

    // Generate preview URL if it's a File or String
    let previewUrl = '';
    if (value instanceof File) {
        previewUrl = URL.createObjectURL(value);
    } else if (typeof value === 'string' && value.trim() !== '') {
        previewUrl = value;
    }

    const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        if (e.target.files && e.target.files[0]) {
            onChange(e.target.files[0]);
        }
    };

    const handleUrlSubmit = () => {
        if (urlInput.trim()) {
            onChange(urlInput.trim());
        }
    };

    const handleClear = (e: React.MouseEvent) => {
        e.stopPropagation();
        setUrlInput('');
        if (fileInputRef.current) {
            fileInputRef.current.value = '';
        }
        if (onRemove) {
            onRemove();
        } else {
            onChange('');
        }
    };

    return (
        <div className="border border-slate-200 rounded-xl p-3 bg-slate-50/70 hover:bg-slate-50 transition-all flex flex-col justify-between">
            <div className="flex justify-between items-center mb-2">
                <span className="text-xs font-bold text-slate-700">{label}</span>
                <button
                    type="button"
                    onClick={() => setIsUrlMode(!isUrlMode)}
                    className="text-[11px] text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-1"
                >
                    {isUrlMode ? <><UploadCloud size={12} /> Upload File</> : <><LinkIcon size={12} /> Paste URL</>}
                </button>
            </div>

            <input
                type="file"
                ref={fileInputRef}
                accept="image/*"
                onChange={handleFileChange}
                className="hidden"
            />

            {previewUrl ? (
                <div className="relative rounded-lg overflow-hidden border border-slate-200 bg-white aspect-video flex items-center justify-center group">
                    <img
                        src={previewUrl}
                        alt="Preview"
                        className="w-full h-full object-contain"
                    />
                    <div className="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                        <button
                            type="button"
                            onClick={() => fileInputRef.current?.click()}
                            className="bg-white text-slate-800 text-xs px-3 py-1.5 rounded-md font-semibold shadow hover:bg-slate-100"
                        >
                            Change
                        </button>
                        <button
                            type="button"
                            onClick={handleClear}
                            className="bg-red-600 text-white text-xs p-1.5 rounded-md shadow hover:bg-red-700"
                        >
                            <X size={14} />
                        </button>
                    </div>
                </div>
            ) : isUrlMode ? (
                <div className="space-y-2 py-2">
                    <input
                        type="text"
                        placeholder="https://example.com/image.jpg"
                        value={urlInput}
                        onChange={e => setUrlInput(e.target.value)}
                        onBlur={handleUrlSubmit}
                        onKeyDown={e => e.key === 'Enter' && (e.preventDefault(), handleUrlSubmit())}
                        className="w-full border border-slate-300 rounded-lg px-2.5 py-2 text-xs bg-white focus:border-indigo-600 outline-none"
                    />
                    <button
                        type="button"
                        onClick={handleUrlSubmit}
                        className="w-full bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs py-1.5 rounded-lg font-semibold transition-colors"
                    >
                        Apply URL
                    </button>
                </div>
            ) : (
                <div
                    onClick={() => fileInputRef.current?.click()}
                    className="border-2 border-dashed border-slate-300 hover:border-indigo-500 rounded-lg p-4 cursor-pointer bg-white transition-all flex flex-col items-center justify-center text-center group aspect-video"
                >
                    <div className="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center mb-1 group-hover:scale-110 transition-transform">
                        <UploadCloud size={18} />
                    </div>
                    <span className="text-xs font-semibold text-slate-700 group-hover:text-indigo-600">
                        Click to Upload
                    </span>
                    <span className="text-[10px] text-slate-400 mt-0.5">
                        PNG, JPG, WEBP up to 5MB
                    </span>
                </div>
            )}
        </div>
    );
}
