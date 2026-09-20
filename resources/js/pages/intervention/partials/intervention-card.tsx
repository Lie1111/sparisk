import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Clock, ImageIcon, Pencil, Repeat, Trash2, ZoomIn } from 'lucide-react';
import { useState } from 'react';
import { LEVEL_ACCENT, programIcon, type Intervention } from './hooks';

export default function InterventionCard({
    item,
    categories,
    onEdit,
    onDelete,
}: {
    item: Intervention;
    categories: Record<string, string>;
    programs?: Record<string, string>;
    onEdit: (item: Intervention) => void;
    onDelete: (item: Intervention) => void;
}) {
    const ProgramIcon = programIcon(item.program);
    const [previewOpen, setPreviewOpen] = useState(false);

    return (
        <div className="group flex flex-col gap-4 rounded-xl border bg-card p-3 transition-shadow hover:shadow-sm sm:flex-row">
            {item.image_src ? (
                <button
                    type="button"
                    onClick={() => setPreviewOpen(true)}
                    title="Click to view a larger image"
                    className="relative h-32 w-full shrink-0 cursor-zoom-in overflow-hidden rounded-lg border bg-muted sm:h-24 sm:w-36"
                >
                    <img
                        src={item.image_src}
                        alt={item.title}
                        loading="lazy"
                        className="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                    />
                    <span className="absolute inset-0 flex items-center justify-center bg-black/50 opacity-0 transition-opacity group-hover:opacity-100">
                        <ZoomIn className="h-5 w-5 text-white" />
                    </span>
                </button>
            ) : (
                <div className="flex h-32 w-full shrink-0 flex-col items-center justify-center gap-1 overflow-hidden rounded-lg border bg-muted text-muted-foreground sm:h-24 sm:w-36">
                    <ImageIcon className="h-5 w-5" />
                    <span className="text-[10px]">No image</span>
                </div>
            )}

            <Dialog open={previewOpen} onOpenChange={setPreviewOpen}>
                <DialogContent className="sm:max-w-3xl">
                    <DialogHeader>
                        <DialogTitle>{item.title}</DialogTitle>
                        <DialogDescription>
                            {item.description || 'Preview of the image the patient sees in the app.'}
                        </DialogDescription>
                    </DialogHeader>
                    <img
                        src={item.image_src ?? ''}
                        alt={item.title}
                        className="max-h-[70vh] w-full rounded-lg border bg-muted object-contain"
                    />
                </DialogContent>
            </Dialog>

            <div className="min-w-0 flex-1">
                <div className="flex flex-wrap items-center gap-2">
                    <ProgramIcon className="h-4 w-4 text-muted-foreground" />
                    <span className="font-medium">{item.title}</span>
                    {item.level && LEVEL_ACCENT[item.level] && (
                        <span
                            className={`rounded-full border px-2 py-0.5 text-[10px] font-medium capitalize ${LEVEL_ACCENT[item.level]}`}
                        >
                            {item.level}
                        </span>
                    )}
                    <Badge variant="outline" className="text-[10px]">
                        {categories[item.category] || item.category}
                    </Badge>
                </div>

                {item.description && (
                    <p className="mt-1 line-clamp-2 text-sm text-muted-foreground">{item.description}</p>
                )}

                <div className="mt-2 flex flex-wrap items-center gap-3 text-xs text-muted-foreground">
                    {item.sets_reps && <span>{item.sets_reps}</span>}
                    {item.frequency && (
                        <span className="inline-flex items-center gap-1">
                            <Repeat className="h-3 w-3" /> {item.frequency}
                        </span>
                    )}
                    {item.duration_minutes !== null && item.duration_minutes !== undefined && (
                        <span className="inline-flex items-center gap-1">
                            <Clock className="h-3 w-3" /> {item.duration_minutes} min
                        </span>
                    )}
                </div>
            </div>

            <div className="flex shrink-0 items-start gap-1">
                <Button variant="ghost" size="sm" className="gap-1" onClick={() => onEdit(item)}>
                    <Pencil className="h-3.5 w-3.5" /> Edit
                </Button>
                <Button
                    variant="ghost"
                    size="sm"
                    className="gap-1 text-destructive"
                    onClick={() => onDelete(item)}
                >
                    <Trash2 className="h-3.5 w-3.5" /> Delete
                </Button>
            </div>
        </div>
    );
}
