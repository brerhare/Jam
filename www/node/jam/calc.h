#ifndef _CALC__H
#define _CALC_H_


double calculate(char * infix);

/*  Macros  */

#define BUFFERSIZE   (256)
#define STACKSIZE    (256)

#define TOK_OPERAND    (0)
#define TOK_OPERATOR   (1)

#define OP_PLUS        (0)
#define OP_MINUS       (1)
#define OP_MULTIPLY    (2)
#define OP_DIVIDE      (3)
#define OP_POWER       (4)
#define OP_MOD         (5)
#define OP_RPAREN      (6)
#define OP_LPAREN      (7)
#define OP_BAD        (99)

/*  Struct definitions  */

struct token {
    int type;
    int value;
};

struct oper {
    char symbol;
    int  value;
    int  precedence;
};

/*  Array of oper descriptions  */
extern struct oper oplist[];


/*  Function declarations  */
char * GetNextToken(char * input, struct token * t);



#endif  /*  _CALC_H_  */

