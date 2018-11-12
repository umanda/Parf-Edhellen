import classNames from 'classnames';
import React from 'react';

import { IProps } from './Gloss._types';

import GlossDetails from './GlossDetails';
import GlossFooter from './GlossFooter';
import GlossInflections from './GlossInflections';
import GlossTitle from './GlossTitle';
import GlossTranslations from './GlossTranslations';
import OldVersionAlert from './OldVersionAlert';

export default class Gloss extends React.PureComponent<IProps> {
    public render() {
        const { gloss, onReferenceLinkClick } = this.props;

        const id = `gloss-block-${gloss.id}`;
        const className = classNames({ contribution: !gloss.isCanon }, 'gloss');
        // const toolbarPlugins = EDConfig.pluginsFor('book-gloss-toolbar');

        return <blockquote itemScope={true} itemType="http://schema.org/Article" id={id} className={className}>
            <OldVersionAlert gloss={gloss} />
            <GlossTitle gloss={gloss} />
            <GlossTranslations gloss={gloss} />
            <GlossDetails gloss={gloss} showDetails={true} onReferenceLinkClick={onReferenceLinkClick} />
            <GlossInflections gloss={gloss} />
            <GlossFooter gloss={gloss} />
        </blockquote>;
    }
}