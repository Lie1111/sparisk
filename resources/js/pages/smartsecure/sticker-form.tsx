import InputError from "@/components/input-error"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"

export default function StickerForm({ data, setData, errors }: any) {
    function updateInputValue(evt: React.ChangeEvent<HTMLInputElement>) {
        const val = evt.target.value
        setData({
            ...data,
            [evt.target.id]: val,
        })
    }

    return (
        <>
            <div className="grid grid-cols-4 items-center gap-4">
                <Label htmlFor="tag" className="text-right">
                    Tag
                </Label>
                <Input
                    id="tag"
                    placeholder="Enter Tag"
                    className="col-span-3"
                    onChange={updateInputValue}
                    value={data.tag || ""}
                />
                {errors.tag && <InputError message={errors.tag} className="col-span-3 col-start-2" />}
            </div>

            <div className="grid grid-cols-4 items-center gap-4">
                <Label htmlFor="label" className="text-right">
                    Label
                </Label>
                <Input
                    id="label"
                    placeholder="Enter Label"
                    className="col-span-3"
                    onChange={updateInputValue}
                    value={data.label || ""}
                />
                {errors.label && <InputError message={errors.label} className="col-span-3 col-start-2" />}
            </div>

            <div className="grid grid-cols-4 items-center gap-4">
                <Label htmlFor="enc" className="text-right">
                    Encryption
                </Label>
                <Input
                    id="enc"
                    placeholder="Enter Encryption"
                    className="col-span-3"
                    onChange={updateInputValue}
                    value={data.enc || ""}
                />
                {errors.enc && <InputError message={errors.enc} className="col-span-3 col-start-2" />}
            </div>
        </>
    )
}

