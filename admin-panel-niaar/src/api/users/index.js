import axiosIns from "@/libs/axios";

export function get({ params }) {
    return axiosIns({
        method: "get",
        params: params,
        url: "/admin/users",
        headers: {
            Authorization:
                "eyJhbGciOiJFUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6InZpc2l0b3ItYXBwbGljYXRpb24tc2VydmVyLTIwMjEwMjIifQ.eyJwaWQiOiI1ODk4Zjg3YmYxYjU3YzBhMDVkNzg2OTYiLCJ2aWQiOiI5MDY5NWY2NzY5NjMyZjIyY2QwNWZhMWExNTdkOTE3NzUyNDYxZWYxOGVjNDRiNjcyMzZiYTRlMDhiM2UxYzI0Iiwic2lkIjoiNjJmMjg1M2U4ODIyNWU5YzQ5MmY3YmEwIiwiaWF0IjoxNjYwMDYwOTkxLCJleHAiOjE2NjAwNjI3OTEsImp0aSI6IkZROEQ2anBTV0xqOWFuV3o1eHlmeCJ9.bMkFKK9lHUiVX0DyU6vifdK8GLIYCqDXeORFQBS3bSr5T7vkUL_w4-Hh0IA0toBIU96zGv6u-4LJ2sXyysIP9g",
        },
    });
}
