// asm_overview.c
void __declspec(naked) main()
{
    // Naked functions must provide their own prolog...
    __asm {
        push ebp
        mov esp, ebp
        sub esp, __LOCAL_SIZE
    }
    
    // ... and epilog
    __asm {
        pop ebp
        ret
    }
}