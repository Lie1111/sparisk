import InputError from "@/components/input-error";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { useState } from "react";
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";

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

            <div className="grid grid-cols-4 items-center gap-4">
                <Label htmlFor="email" className="text-right">
                    Email
                </Label>
                <Input
                    id="email"
                    placeholder="Enter user Email"
                    className="col-span-3"
                    onChange={(evt) => updateInputValue(evt)}
                    value={props.data.email ? props.data.email : ""}
                />
                <div className="items-center gap-4 text-right">
                    <InputError message={props.errors.email} />
                </div>
            </div>

            {props.formType === "create" &&
                ["password", "password_confirmation"].map((field) => (
                    <div key={field}>
                        <div
                            className="grid grid-cols-4 items-center gap-4"
                        >
                            <Label htmlFor={field} className="text-right">
                                {field === "password_confirmation"
                                    ? "Confirm Password"
                                    : "Password"}
                            </Label>
                            <Input
                                id={field}
                                placeholder="******"
                                type="password"
                                className="col-span-3"
                                onChange={updateInputValue}
                                value={props.data[field] || ""}
                                autoComplete={
                                    field === "password_confirmation"
                                        ? "new-password"
                                        : "confirmed-password"
                                }
                            />

                        </div>
                        <div className="text-right">
                            <InputError message={props.errors[field]} />
                        </div>
                    </div>

                ))}

            <></>

            <div className="grid grid-cols-4 items-center gap-4">
                <Label htmlFor="role" className="text-right">
                    Role
                </Label>
                <Select
                    onValueChange={(value) =>
                        props.setData({
                            ...props.data,
                            ['role']: value,
                        })
                    }
                    defaultValue={props.data.role || ""}
                >
                    <SelectTrigger className="col-span-3">
                        <SelectValue placeholder="Select Role" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectGroup>
                            {props.allRoles.map((r: any, index: string) => {
                                return (
                                    <SelectItem key={index} value={r.name.toString()}>
                                        {r.name}
                                    </SelectItem>
                                )
                            })}
                        </SelectGroup>
                    </SelectContent>
                </Select>
            </div>
            <InputError message={props.errors.role} />
        </>
    );
}