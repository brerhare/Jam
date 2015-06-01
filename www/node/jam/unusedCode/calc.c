#include <ctype.h>
#include <stdlib.h>
#include <stdio.h>
#include <string.h>
#include <math.h>

#include "calc.h"


#define BUFFERSIZE (256)

int main3(void) {
    char input[BUFFERSIZE];
    
    printf("Enter your expression: ");
    fflush(stdout);
    fgets(input, BUFFERSIZE, stdin);

    printf("Result is: %.2f\n", calculate(input));

    return EXIT_SUCCESS;
}

/*  Converts a string based infix numeric expression
    to a string based postfix numeric expression      */

char * toPostfix(char * infix, char * postfix) {
    char         buffer[BUFFERSIZE];
    int          stack[STACKSIZE];
    int          top = 0;
    struct token t;
    
    *postfix = 0;                         /*  Empty output buffer     */

    while ( (infix = GetNextToken(infix, &t)) ) {
        if ( t.type == TOK_OPERAND ) {

            /*  Always output operands immediately  */

            sprintf(buffer, "%d ", t.value);
            strcat(postfix, buffer);
        }
        else {

            /*  Add the operator to the stack if:
                 - the stack is empty; OR
                 - if the operator has a higher precedence than
                   the operator currently on top of the stack; OR
                 - An opening parenthesis is on top of the stack   */

            if ( top == 0 || oplist[t.value].precedence >
                             oplist[stack[top]].precedence ||
                             oplist[stack[top]].value == OP_LPAREN ) {
                stack[++top] = t.value;
            }
            else {
                int balparen = 0;

                /*  Otherwise, remove all operators from the stack
                    which have a higher precedence than the current
                    operator. If we encounter a closing parenthesis,
                    keep removing operators regardless of precedence
                    until we find its opening parenthesis.            */

                while ( top > 0 && ((oplist[stack[top]].precedence >=
                                     oplist[t.value].precedence)
                                    || balparen)
                                && !(!balparen
                                     && oplist[stack[top]].value ==
                                        OP_LPAREN) ) {
                    if ( stack[top] == OP_RPAREN )
                        ++balparen;
                    else if ( stack[top] == OP_LPAREN )
                        --balparen;
                    else {
                        sprintf(buffer, "%c ", oplist[stack[top]].symbol);
                        strcat(postfix, buffer);
                    }
                    --top;
                }
                stack[++top] = t.value;
            }
        }
    }


    /*  Output any operators still on the stack  */

    while ( top > 0 ) {
        if ( oplist[stack[top]].value != OP_LPAREN &&
             oplist[stack[top]].value != OP_RPAREN ) {
            sprintf(buffer, "%c ", oplist[stack[top]].symbol);
            strcat(postfix, buffer);
        }
        --top;
    }

    return postfix;
}    


/*  Parses a postfix expression and returns its value  */

double parsePostfix(char * postfix) {
    struct token t;
    double       stack[STACKSIZE];
    int          top = 0;
    
    while ( (postfix = GetNextToken(postfix, &t)) ) {
        if ( t.type == TOK_OPERAND ) {
            stack[++top] = t.value;       /*  Store operand on stack  */
        }
        else {
            double a, b, value;
            
            if ( top < 2 ) {              /*  Two operands on stack?  */
                puts("Stack empty!");
                exit(EXIT_FAILURE);
            }

            b = stack[top--];             /*  Get last two operands   */
            a = stack[top--];

            switch ( t.value ) {
            case OP_PLUS:                 /*  Perform operation       */
                value = a + b;
                break;
                
            case OP_MINUS:
                value = a - b;
                break;
                
            case OP_MULTIPLY:
                value = a * b;
                break;
                
            case OP_DIVIDE:
                value = a / b;
                break;

            case OP_MOD:
                value = fmod(a, b);
                break;
                
            case OP_POWER:
                value = pow(a, b);
                break;
                
            default:
                printf("Bad operator: %c\n", oplist[t.value].symbol);
                exit(EXIT_FAILURE);
                break;
            }            
            stack[++top] = value;        /*  Put value back on stack  */
        }
    }
    return stack[top];
}
            

/*  Evaluates an postfix numeric expression  */

double calculate(char * infix) {
    char postfix[BUFFERSIZE];
    return parsePostfix(toPostfix(infix, postfix));
}



/***** TOKEN *****/

/*  Array of operators  */

struct oper oplist[] = { {'+', OP_PLUS,     1},
                             {'-', OP_MINUS,    1},
                             {'*', OP_MULTIPLY, 2},
                             {'/', OP_DIVIDE,   2},
                             {'^', OP_POWER,    3},
                             {'%', OP_MOD,      2},
                             {')', OP_RPAREN,   4},
                             {'(', OP_LPAREN,   5},
                             {0,   0,           0} };


/*  Gets the next token from a string based numeric
    expression. Returns the address of the first
    character after the token found.                 */

char * GetNextToken(char * input, struct token * t) {
    while ( *input && isspace(*input) )  /*  Skip leading whitespace  */
        ++input;

    if ( *input == 0 )                   /*  Check for end of input   */
        return NULL;

    if ( isdigit(*input) ) {             /*  Token is an operand      */
        t->type  = TOK_OPERAND;
        t->value = strtol(input, &input, 0);
    }
    else {                               /*  Token is an operator     */
        int n = 0, found = 0;

        t->type = TOK_OPERATOR;

        while ( !found && oplist[n].symbol ) {
            if ( oplist[n].symbol == *input ) {
                t->value = oplist[n].value;
                found = 1;
            }
            ++n;
        }

        if ( !found ) {
            printf("Bad operator: %c\n", *input);
            exit(EXIT_FAILURE);
        }
        ++input;
    }        
    return input;
}
