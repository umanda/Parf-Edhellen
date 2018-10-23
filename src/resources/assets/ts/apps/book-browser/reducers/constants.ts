import {
    IReduxAction,
    IReduxActionableState,
} from '../../../_types/redux';
import {
    IFindActionRequest,
} from '../../../connectors/backend/BookApiConnector';

export enum Actions {
    RequestSearchResults = 'ED_SEARCH_RESULT_REQUEST',
    ReceiveSearchResults = 'ED_SEARCH_RESULT',
}

export interface ISearchResultState {
    normalizedWord: string;
    originalWord: string;
    word: string;
}

export type ISearchActionState = IFindActionRequest;

export type ISearchState = ISearchActionState & {
    itemIndex?: number;
    loading?: boolean;
};

export interface ISearchAction extends IReduxActionableState<ISearchState> {
}

export interface ISearchResultAction extends IReduxAction {
    items: ISearchResultState[];
}