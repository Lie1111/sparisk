import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Pencil, Trash2 } from 'lucide-react';
import type { Intervention } from './hooks';

export default function InterventionCard({
    item,
    categories,
    onEdit,
    onDelete,
}: {
    item: Intervention;
    categories: Record<string, string>;
    onEdit: (item: Intervention) => void;
    onDelete: (item: Intervention) => void;
}) {
    return (
        <div className="flex items-start gap-4 rounded-lg border p-3">
            <div className="flex-1">
                <div className="flex items-center gap-2">
                    <span className="font-medium">{item.title}</span>
                    <Badge variant="outline" className="text-[10px]">
                        {categories[item.category] || item.category}
                    </Badge>
                </div>
                {item.description && (
                    <p className="mt-1 text-sm text-muted-foreground">{item.description}</p>
                )}
                <div className="mt-1 flex flex-wrap gap-x-4 gap-y-1 text-xs text-muted-foreground">
                    {item.sets_reps && <span>Sets/Reps: {item.sets_reps}</span>}
                    {item.frequency && <span>Frequency: {item.frequency}</span>}
                    {item.duration_minutes && <span>{item.duration_minutes} min</span>}
                </div>
            </div>
            <div className="flex shrink-0 items-center gap-1">
                <Button variant="ghost" size="sm" className="gap-1" onClick={() => onEdit(item)}>
                    <Pencil className="h-3.5 w-3.5" /> Edit
                </Button>
                <Button variant="ghost" size="sm" className="gap-1 text-destructive" onClick={() => onDelete(item)}>
                    <Trash2 className="h-3.5 w-3.5" /> Delete
                </Button>
            </div>
        </div>
    );
}
