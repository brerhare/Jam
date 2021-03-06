#ifndef WORDHTML_H_INCLUDED
#define WORDHTML_H_INCLUDED

int wordHtmlSys(int ix, char *defaultTableName);
int wordHtmlJs(int ix, char *defaultTableName);

int wordHtmlBreakpoint(int ix, char *defaultTableName);

int wordHtmlDropdown(int ix, char *defaultTableName);
int wordHtmlFilter(int ix, char *defaultTableName);
int wordHtmlInput(int ix, char *defaultTableName);
int wordHtmlCheckbox(int ix, char *defaultTableName);
int wordHtmlRadio(int ix, char *defaultTableName);
int wordHtmlTabs(int ix, char *defaultTableName);

int wordHtmlContainer(int ix, char *defaultTableName);
int wordHtmlForm(int ix, char *defaultTableName);
int wordHtmlGridrow(int ix, char *defaultTableName);
int wordHtmlGridcol(int ix, char *defaultTableName);

int wordHtmlInputOld(int ix, char *defaultTableName);
int wordHtmlInp(int ix, char *defaultTableName);
int wordHtmlGridInp(int ix, char *defaultTableName);
int wordHtmlTextarea(int ix, char *defaultTableName);
int wordHtmlButton(int ix, char *defaultTableName);
int wordHtmlSelect(int ix, char *defaultTableName);

#endif	// WORDHTML_H_INCLUDED
