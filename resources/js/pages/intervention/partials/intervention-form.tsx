import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { ImageIcon, Link2, Trash2, Upload } from 'lucide-react';
import { useEffect, useRef, useState } from 'react';
import { Button } from '@/components/ui/button';
import { LEVEL_ACCENT, programIcon, type InterventionFormData } from './hooks';

/** Sentinel because Radix Select forbids an empty-string item value. */
const NO_LEVEL = 'none';

export default function InterventionForm({
    data,
    setData,
    errors,
    categories,
    programs,
    levels,
    imageFile,
    onImageFileChange,
    onRemoveImage,
    currentImage,
}: {
    data: InterventionFormData;
    setData: (key: keyof InterventionFormData, value: any) => void;
    errors?: Record<string, string>;
    categories: Record<string, string>;
    programs?: Record<string, string>;
    levels?: Record<string, string>;
    imageFile?: File | null;
    onImageFileChange?: (file: File | null) => void;
    onRemoveImage?: () => void;
    /** Image already saved on the row, shown when nothing new is picked. */
    currentImage?: string | null;
}) {
    const fieldError = (key: string) => (errors as any)?.[key];

    const fileInput = useRef<HTMLInputElement>(null);
    const [objectUrl, setObjectUrl] = useState<string | null>(null);

    // A freshly picked file wins over the saved image, so the admin sees the
    // replacement straight away.
    useEffect(() => {
        if (!imageFile) {
            setObjectUrl(null);
            return;
        }
        const url = URL.createObjectURL(imageFile);
        setObjectUrl(url);
        return () => URL.revokeObjectURL(url);
    }, [imageFile]);

    const preview = objectUrl ?? data.image_url ?? currentImage ?? null;
    const isMassage = data.program === 'massage_therapy';
    const ProgramIcon = programIcon(data.program);

    return (
        <div className="space-y-5">
            {programs && (
                <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <Label>Program</Label>
                        <Select value={data.program} onValueChange={(v) => setData('program', v)}>
                            <SelectTrigger>
                                <SelectValue />
                            </SelectTrigger>
                            <SelectContent>
                                {Object.entries(programs).map(([key, label]) => (
                                    <SelectItem key={key} value={key}>{label}</SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                        {fieldError('program') && <p className="text-xs text-red-500">{fieldError('program')}</p>}
                    </div>
                    <div>
                        <Label>Level</Label>
                        <Select
                            value={data.level === '' ? NO_LEVEL : data.level}
                            onValueChange={(v) => setData('level', v === NO_LEVEL ? '' : v)}
                        >
                            <SelectTrigger>
                                <SelectValue placeholder="Optional" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value={NO_LEVEL}>No level</SelectItem>
                                {Object.entries(levels ?? {}).map(([key, label]) => (
                                    <SelectItem key={key} value={key}>{label}</SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                        {fieldError('level') && <p className="text-xs text-red-500">{fieldError('level')}</p>}
                    </div>
                </div>
            )}

            <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <Label>{isMassage ? 'Body Area' : 'Title'}</Label>
                    <Input
                        value={data.title}
                        onChange={(e) => setData('title', e.target.value)}
                        placeholder={isMassage ? 'e.g. Neck' : 'e.g. Chin Tuck Float'}
                    />
                    {fieldError('title') && <p className="text-xs text-red-500">{fieldError('title')}</p>}
                </div>
                <div>
                    <Label>Category</Label>
                    <Select value={data.category} onValueChange={(v) => setData('category', v)}>
                        <SelectTrigger>
                            <SelectValue />
                        </SelectTrigger>
                        <SelectContent>
                            {Object.entries(categories).map(([key, label]) => (
                                <SelectItem key={key} value={key}>{label}</SelectItem>
                            ))}
                        </SelectContent>
                    </Select>
                    {fieldError('category') && <p className="text-xs text-red-500">{fieldError('category')}</p>}
                </div>
            </div>

            <div>
                <Label>Description</Label>
                <Textarea
                    value={data.description}
                    onChange={(e) => setData('description', e.target.value)}
                    rows={3}
                    placeholder={isMassage ? 'How the massage is performed…' : 'Instructions for the patient…'}
                />
            </div>

            <div className="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <Label>Sets / Reps</Label>
                    <Input
                        value={data.sets_reps}
                        onChange={(e) => setData('sets_reps', e.target.value)}
                        placeholder="3 x 10 reps"
                    />
                </div>
                <div>
                    <Label>Frequency</Label>
                    <Input
                        value={data.frequency}
                        onChange={(e) => setData('frequency', e.target.value)}
                        placeholder="3x per week"
                    />
                </div>
                <div>
                    <Label>Duration (min)</Label>
                    <Input
                        type="number"
                        min={0}
                        value={data.duration_minutes}
                        onChange={(e) => setData('duration_minutes', e.target.value)}
                        placeholder="10"
                    />
                    {fieldError('duration_minutes') && (
                        <p className="text-xs text-red-500">{fieldError('duration_minutes')}</p>
                    )}
                </div>
            </div>

            <div className="rounded-lg border bg-muted/30 p-4">
                <div className="mb-3 flex items-center gap-2">
                    <ProgramIcon className="h-4 w-4 text-muted-foreground" />
                    <Label className="mb-0">Image shown in the app</Label>
                    {data.level && LEVEL_ACCENT[data.level] && (
                        <span className={`ml-auto rounded-full border px-2 py-0.5 text-[10px] font-medium capitalize ${LEVEL_ACCENT[data.level]}`}>
                            {data.level}
                        </span>
                    )}
                </div>

                <div className="flex flex-col gap-4 sm:flex-row">
                    <div className="flex h-28 w-full shrink-0 items-center justify-center overflow-hidden rounded-md border bg-background sm:w-44">
                        {preview ? (
                            <img src={preview} alt="Preview" className="h-full w-full object-cover" />
                        ) : (
                            <div className="flex flex-col items-center gap-1 text-muted-foreground">
                                <ImageIcon className="h-6 w-6" />
                                <span className="text-[11px]">No image</span>
                            </div>
                        )}
                    </div>

                    <div className="flex-1 space-y-3">
                        <div className="flex flex-wrap items-center gap-2">
                            <input
                                ref={fileInput}
                                type="file"
                                accept="image/*"
                                className="hidden"
                                onChange={(e) => onImageFileChange?.(e.target.files?.[0] ?? null)}
                            />
                            <Button
                                type="button"
                                variant="outline"
                                size="sm"
                                className="gap-1"
                                onClick={() => fileInput.current?.click()}
                            >
                                <Upload className="h-3.5 w-3.5" />
                                {imageFile ? 'Change file' : 'Upload image'}
                            </Button>
                            {preview && (
                                <Button
                                    type="button"
                                    variant="ghost"
                                    size="sm"
                                    className="gap-1 text-destructive"
                                    onClick={() => {
                                        onImageFileChange?.(null);
                                        if (fileInput.current) fileInput.current.value = '';
                                        onRemoveImage?.();
                                    }}
                                >
                                    <Trash2 className="h-3.5 w-3.5" /> Remove
                                </Button>
                            )}
                            {imageFile && (
                                <span className="truncate text-xs text-muted-foreground">{imageFile.name}</span>
                            )}
                        </div>

                        <div>
                            <Label className="flex items-center gap-1 text-xs text-muted-foreground">
                                <Link2 className="h-3 w-3" /> …or paste an image URL
                            </Label>
                            <Input
                                className="mt-1"
                                value={data.image_url}
                                onChange={(e) => setData('image_url', e.target.value)}
                                placeholder="https://example.com/exercise.jpg"
                            />
                            {fieldError('image_url') && (
                                <p className="text-xs text-red-500">{fieldError('image_url')}</p>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
