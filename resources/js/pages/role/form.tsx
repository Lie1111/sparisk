
import InputError from "@/components/input-error";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { useState } from "react";

export default function Form(props: any) {
    function updateInputValue(evt: any, type?: string) {
        const val = evt.target.value;

        props.setData({
            ...props.data,
            [evt.target.id]: val,
        });
    }

    return (
        <>
            <div className="grid grid-cols-4 items-center gap-4">
                <Label htmlFor="name" className="text-right">
                    Name
                </Label>
                <Input
                    id="name"
                    placeholder="Enter user Name"
                    className="col-span-3"
                    onChange={(evt) => updateInputValue(evt)}
                    value={props.data.name ? props.data.name : ""}
                />
                <div className="items-center gap-4 text-right">
                    <InputError message={props.errors.name} />
                </div>
            </div>
        </>
    );
}